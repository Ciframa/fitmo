<template>
  <div>
    <h2>Přehled produktů</h2>
    <ul class="productsList">
      <template v-for="product in products">
        <li>
          <div class="productsList__nameImageWrapper">
            <img
              v-if="product.image_urls"
              :src="
                product.color_name
                  ? this.imagesBasePath +
                    product.name +
                    '-' +
                    product.color_name +
                    '/' +
                    product.image_urls[0]
                  : product.variant
                  ? this.imagesBasePath +
                    product.name +
                    '-' +
                    product.variant +
                    '/' +
                    product.image_urls[0]
                  : this.imagesBasePath +
                    product.name +
                    '/' +
                    product.image_urls[0]
              "
              alt=""
            />
            <span class="productsList__name__wrapper">
              <span class="productsList__name">
                {{
                  product.color_name || product.variant
                    ? product.name + ", "
                    : product.name
                }}
                {{ product.color_name ?? "" }}
                {{ product.variant ?? "" }}
              </span>
              <ul class="productsList__name__wrapper__buttons">
                <template
                  v-if="
                    product.name !== 'Nezařazeny' &&
                    product.name !== 'Bez kategorií'
                  "
                >
                  <router-link :to="'/edit/' + product.id"
                    >Editovat</router-link
                  >
                  <li v-on:click="hideProduct(product.id)">
                    {{ product.isActive ? "Schovat" : "Zobrazovat" }}
                  </li>
                  <li v-on:click="deleteProduct(product.id)">
                    Smazat
                  </li> </template
                ><font-awesome-icon
                  v-if="product.children?.length > 0"
                  :icon="['fa', 'angle-down']"
                  :size="'2x'"
                  v-on:click="product.showChildren = !product.showChildren"
                  :class="{ rotated: product.showChildren }"
                />
              </ul>
            </span>
          </div>
          <ul v-if="product.showChildren">
            <li v-for="child in product.children" :key="child?.id">
              <img
                v-if="child?.image_urls"
                :src="
                  child?.color_name
                    ? this.imagesBasePath +
                      child?.name +
                      '-' +
                      child?.color_name +
                      '/' +
                      child?.image_urls[0]
                    : child?.variant
                    ? this.imagesBasePath +
                      child?.name +
                      '-' +
                      child?.variant +
                      '/' +
                      child?.image_urls[0]
                    : this.imagesBasePath +
                      child?.name +
                      '/' +
                      child?.image_urls[0]
                "
                alt=""
              />
              <span class="productsList__name__wrapper">
                <span class="productsList__name">
                  {{
                    child?.color_name || child?.variant
                      ? child?.name + ", "
                      : child?.name
                  }}
                  {{ child?.color_name ?? "" }}
                  {{ child?.variant ?? "" }}
                </span>
                <ul class="productsList__name__wrapper__buttons">
                  <router-link :to="'/edit/' + child?.id">Editovat</router-link>
                  <li v-on:click="hideProduct(product.id)">
                    {{ product.isActive ? "Schovat" : "Zobrazovat" }}
                  </li>

                  <li v-on:click="deleteProduct(child?.id)">Smazat</li>
                </ul>
              </span>
            </li>
          </ul>
        </li>
      </template>
    </ul>
  </div>
</template>

<script>
import axios from "../../api";

export default {
  components: {},
  data() {
    return {
      products: [],
      imagesBasePath: "https://be.fitmo.cz/products/",
      // imagesBasePath: "http://localhost:8000/products/",
    };
  },

  methods: {
    async getAllProducts() {
      try {
        const response = await axios.get("/api/soloProducts");

        this.products = response.data;
        console.log(response.data.filter((item) => item.parent_id == null));
      } catch (error) {
        console.log(error);
      }
    },
    async deleteProduct(id) {
      await axios
        .delete("/api/product/" + id, {
          headers: {
            Authorization: "",
          },
        })
        .then((response) => {
          if (response.status === 200) {
            this.getAllProducts();
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
          } else {
            this.$snackbar.add({
              type: "error",
              text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
            });
          }
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text:
              error.response.data.error ??
              "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
          console.log(error);
        });
    },

    async hideProduct(id) {
      await axios
        .put("/api/product/" + id + "/hide", {
          headers: {
            Authorization: "",
          },
        })
        .then((response) => {
          if (response.status === 200) {
            this.getAllProducts();
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
          } else {
            this.$snackbar.add({
              type: "error",
              text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },

  mounted() {
    this.getAllProducts();
  },
};
</script>
<style lang="scss">
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

  & > li {
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
