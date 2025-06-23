<template>
  <div :class="sizes">
    <template v-for="product in products" :key="product.id">
      <router-link
        :to="{
          name: 'Product',
          params: {
            productPath: product.url_name,
            // categoryPath: product.categoryPath,
          },
        }"
        v-if="product.isMain === 1"
      >
        <span
          class="home__eshop__wrapper__item_discount"
          v-if="product.discountedPercent"
          >{{ product.discountedPercent }}%</span
        >
        <div class="home__eshop__wrapper__img_wrapper">
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
          <img
            v-if="product.image_urls[1] && product.color_id === null"
            :src="
              product.color_name
                ? this.imagesBasePath +
                  product.name +
                  '-' +
                  product.color_name +
                  '/' +
                  product.image_urls[1]
                : product.variant
                ? this.imagesBasePath +
                  product.name +
                  '-' +
                  product.variant +
                  '/' +
                  product.image_urls[1]
                : this.imagesBasePath +
                  product.name +
                  '/' +
                  product.image_urls[1]
            "
            alt=""
          />
        </div>
        <div class="info_wrapper">
          <div
            class="my-row center home__eshop__wrapper__img_wrapper__subProducts__wrapper"
            v-on:mouseleave="changeProduct(variant, 'leave')"
          >
            <div v-for="variant in products" :key="variant.id">
              <div
                class="home__eshop__wrapper__img_wrapper__subProducts"
                :class="{ active: variant.isMain == 1 }"
                v-on:mouseover="changeProduct(variant, 'over')"
                v-if="variant.color_id"
              >
                <div
                  v-if="variant.color_primary"
                  :style="{ backgroundColor: variant.color_primary }"
                ></div>
                <div
                  v-if="variant.color_secondary"
                  :style="{ backgroundColor: variant.color_secondary }"
                ></div>
              </div>
            </div>
          </div>
          <h4>
            {{ product.name }}
          </h4>
          <span class="home__eshop__wrapper__name"
            >{{ product.description }}
          </span>
          <div
            v-if="getLowestPrice()['discounted']"
            class="home__eshop__wrapper__price"
          >
            <span class="home__eshop__wrapper__price__trough"
              >{{ getLowestPrice()["originalPrice"] }} Kč</span
            >
            <span class="home__eshop__wrapper__price__discount"
              >{{ getLowestPrice()["price"] }} Kč</span
            >
          </div>
          <div
            v-if="!getLowestPrice()['discounted']"
            class="home__eshop__wrapper__price"
          >
            <span
              >{{ this.products.length > 1 ? "od" : "" }}
              {{ getLowestPrice()["price"] }} Kč</span
            >
          </div>
          <!--        <div class="home__eshop__wrapper__discounts">-->
          <!--          <span v-if="product.discount" class="btn-yellow">Akce</span>-->
          <!--          <span v-if="product.topProduct" class="btn-green">Top produkt</span>-->
          <!--          <span v-if="product.newProduct" class="btn-blue">Novinka</span>-->
          <!--        </div>-->
        </div>
      </router-link>
    </template>
  </div>
</template>
<script>
export default {
  data() {
    return {
      imagesBasePath: `https://be.fitmo.cz/products/`,
    };
  },
  props: {
    sizes: String,
    products: Object,
  },
  methods: {
    changeProduct(variant, mouse) {
      if (mouse == "over") {
        this.products.forEach((product) => {
          if (product.id !== variant.id) {
            product.isMain = 0;
          } else {
            product.isMain = 1;
          }
        });
      } else {
        this.products.forEach((product) => {
          product.isMain = 0;
        });
        this.products[0].isMain = 1;
      }
    },
    getLowestPrice() {
      let lowestDiscounted = null;
      let originalOfDiscounted = null;
      let lowestNormal = null;

      this.products.forEach((product) => {
        if (!(product.price === 0)) {
          if (product.discounted !== null) {
            if (
              lowestDiscounted === null ||
              lowestDiscounted > product.discounted
            ) {
              lowestDiscounted = product.discounted;
              originalOfDiscounted = product.price;
            }
          } else if (product.price !== null) {
            if (lowestNormal === null || lowestNormal > product.price) {
              lowestNormal = product.price;
            }
          }
        }
      });

      if (
        lowestDiscounted !== null &&
        (lowestNormal === null || lowestDiscounted < lowestNormal)
      ) {
        return {
          price: lowestDiscounted,
          originalPrice: originalOfDiscounted,
          discounted: true,
        };
      } else {
        return {
          price: lowestNormal,
          discounted: false,
        };
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.center {
  justify-content: center;
  gap: 0.6rem;
}

.info_wrapper {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

a {
  height: 100%;
}
</style>
