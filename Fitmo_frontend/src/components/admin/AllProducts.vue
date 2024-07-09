<template>
  <div>
    <h2>Přehled produktů</h2>
    <ul class="productsList">
      <template v-for="product in products">
        <li v-if="!product.isChildren" :key="product.id">
          <img
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
              <router-link :to="'/edit/' + product.id">Editovat</router-link>
              <li v-on:click="deleteProduct(product.id)">Smazat</li>
              <font-awesome-icon
                v-if="product.children?.length > 0"
                :icon="['fa', 'angle-down']"
                :size="'2x'"
                v-on:click="product.showChildren = !product.showChildren"
                :class="{ rotated: product.showChildren }"
              />
            </ul>
            <template v-if="product.showChildren">
              <li v-for="child in product.children" :key="child.id">
                <img
                  :src="
                    child.color_name
                      ? this.imagesBasePath +
                        child.name +
                        '-' +
                        child.color_name +
                        '/' +
                        product.image_urls[0]
                      : child.variant
                      ? this.imagesBasePath +
                        child.name +
                        '-' +
                        child.variant +
                        '/' +
                        child.image_urls[0]
                      : this.imagesBasePath +
                        child.name +
                        '/' +
                        child.image_urls[0]
                  "
                  alt=""
                />
                <span class="productsList__name__wrapper">
                  <span class="productsList__name">
                    {{
                      child.color_name || child.variant
                        ? child.name + ", "
                        : child.name
                    }}
                    {{ child.color_name ?? "" }}
                    {{ child.variant ?? "" }}
                  </span>
                  <ul class="productsList__name__wrapper__buttons">
                    <router-link :to="'/edit/' + child.id"
                      >Editovat</router-link
                    >
                    <li v-on:click="deleteProduct(child.id)">Smazat</li>
                  </ul>
                </span>
              </li>
            </template>
          </span>
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
      imagesBasePath: process.env.VUE_APP_FITMO_BACKEND_URL + "/products/",
    };
  },

  methods: {
    async getAllProducts() {
      try {
        const response = await axios.get("/api/soloProducts");
        response.data.forEach((product) => {
          product.children = response.data
            .filter((productItem) => {
              productItem.showChildren = false;
              return product.id === productItem.parent_id;
            })
            .map((child) => {
              child.isChildren = 1;
              return child;
            });
        });

        this.products = response.data;
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
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },

  created() {
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

  .rotated {
    transform: rotateX(180deg);
  }
  & > li {
    border-bottom: 1px solid $gray-second;
    padding: 1rem 0;
    display: flex;

    li {
      display: flex;
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
      }
    }
  }
}
</style>
