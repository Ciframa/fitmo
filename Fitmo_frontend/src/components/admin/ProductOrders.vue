<template>
  <div>
    <input
      type="submit"
      v-on:click="updateOrderOfProducts()"
      value="Upravit pořadí produktů"
      class="btn-yellow"
    />
    <h2>Pořadí produktů</h2>
    <ul class="productsList">
      <template v-for="category in productByCategories">
        <h4>{{ category.name }}</h4>
        <draggable
          class="dragArea"
          tag="div"
          :list="category.products"
          :group="{ name: `g1-${category.name}`, put: false, pull: false }"
          item-key="name"
        >
          <template #item="{ element }">
            <li
              :style="{
                listStyleType: 'none',
              }"
            >
              <div class="productsList__nameImageWrapper">
                <img
                  v-if="element.image_urls"
                  :src="
                    element.color_name
                      ? this.imagesBasePath +
                        element.name +
                        '-' +
                        element.color_name +
                        '/' +
                        element.image_urls[0]
                      : element.variant
                      ? this.imagesBasePath +
                        element.name +
                        '-' +
                        element.variant +
                        '/' +
                        element.image_urls[0]
                      : this.imagesBasePath +
                        element.name +
                        '/' +
                        element.image_urls[0]
                  "
                  alt=""
                />
                <span class="productsList__name__wrapper">
                  <span class="productsList__name">
                    {{
                      element.color_name || element.variant
                        ? element.name + ", "
                        : element.name
                    }}
                    {{ element.color_name ?? "" }}
                    {{ element.variant ?? "" }}
                  </span>
                  <ul class="productsList__name__wrapper__buttons">
                    <template
                      v-if="
                        element.name !== 'Nezařazeny' &&
                        element.name !== 'Bez kategorií'
                      "
                    >
                      <router-link :to="'/edit/' + element.id"
                        >Editovat</router-link
                      >
                      <!--                  <li v-on:click="hideProduct(element.id)">-->
                      <!--                    {{ element.isActive ? "Schovat" : "Zobrazovat" }}-->
                      <!--                  </li>-->
                      <!--                  <li v-on:click="deleteProduct(element.id)">-->
                      <!--                    Smazat-->
                      <!--                  </li> -->
                    </template>
                  </ul>
                </span>
              </div>
            </li>
          </template>
        </draggable>
      </template>
    </ul>
  </div>
</template>

<script>
import axios from "../../api";
import draggable from "vuedraggable";

export default {
  components: { draggable },
  data() {
    return {
      productByCategories: [],
      imagesBasePath: "https://be.fitmo.cz/products/",
    };
  },
  methods: {
    async getProductByCategories() {
      try {
        const response = await axios.get("/api/productsByCategories");
        this.productByCategories = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    updateOrderOfProducts() {
      // update product by order: number by order in each category
      this.productByCategories.forEach((category) => {
        category.products.forEach((product, index) => {
          product.order = index;
        });
      });
      axios
        .post("/api/updateOrderOfProducts", {
          categories: this.productByCategories,
        })
        .then((response) => {
          if (response.status == 200 || response.status == 201) {
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
          }
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
        });
    },
  },

  mounted() {
    this.getProductByCategories();
  },
};
</script>
<style lang="scss" scoped>
.productsList {
  gap: 1rem;
  display: flex;
  flex-direction: column;

  img {
    width: 80px;
    object-fit: contain;
    object-position: top;
  }

  &__nameImageWrapper {
    display: flex;
    gap: 1rem;
  }

  .rotated {
    transform: rotateX(180deg);
  }

  & .dragArea > li {
    border-bottom: 1px solid $gray-second;
    padding: 1rem 0;
    display: flex;
    flex-direction: column;

    li {
      display: flex;
      gap: 1rem;
      padding-left: 1rem;

      > ul {
        display: flex;
        flex-direction: column;
      }
    }
  }

  &__name {
    font-weight: 500;
    font-size: 1.5rem;

    &__wrapper {
      &__buttons {
        display: flex;
        gap: 0.5rem;
        font-size: 1.3rem;

        & > li {
          cursor: pointer;
        }
      }
    }
  }
}
</style>
