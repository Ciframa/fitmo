<template>
  <div v-if="isLoading">Načítání produktu...</div>
  <div class="product" v-else-if="this.products[0]">
    <ul class="category__header__navigation">
      <li>
        <router-link :to="'/'">
          <font-awesome-icon :icon="['fa', 'house']" />
        </router-link>
        <font-awesome-icon :icon="['fa', 'angle-down']" rotation="270" />
      </li>
      <template v-if="this.products[0]">
        <li
          v-for="(link, index) in this.getNavigation(
            this.products[0][0].category_path
          )"
          :key="index"
        >
          <font-awesome-icon
            v-if="index !== 0"
            :icon="['fa', 'angle-down']"
            rotation="270"
          />
          <router-link :to="'/kategorie/' + link.url_path">
            {{ link.name }}
          </router-link>
        </li>
        <li>
          <font-awesome-icon
            v-if="index !== 0"
            :icon="['fa', 'angle-down']"
            rotation="270"
          />
          <router-link :to="''">
            {{ products[0][0].name }}
          </router-link>
        </li>
      </template>
    </ul>
    <div class="product__header">
      <template v-for="product in products[0]">
        <div
          class="product__header__item my-row"
          :key="product.id"
          v-if="product.isMain === 1"
        >
          <div class="product__header__item__img_wrapper">
            <span
              v-if="product.discountPercent"
              class="home__eshop__wrapper__item_discount"
              >-{{ product.discountPercent }}%</span
            >
            <div class="product__header__item__img_wrapper__more">
              <ImagesSlider
                :images="product.image_urls"
                :imageBasePath="this.imagesBasePath"
                :productName="product.name"
                :productVariant="product.variant"
                :colorName="product.color_name"
              />
            </div>
          </div>
          <div class="product__header__item__header">
            <h1>{{ product.name }}</h1>
            <!--            <h1 v-if="!product.color_name">{{ product.name }}</h1>-->
            <!--            <h1 v-if="product.color_name">-->
            <!--              {{ product.name }}, {{ product.color_name }}-->
            <!--            </h1>-->
            <h2>{{ product.description }}</h2>
            <!--            <div class="product__header__rating">-->
            <!--              <font-awesome-icon :icon="['fa', 'star']" />-->
            <!--              <font-awesome-icon :icon="['fa', 'star']" />-->
            <!--              <font-awesome-icon :icon="['fa', 'star']" />-->
            <!--              <font-awesome-icon :icon="['fa', 'star']" />-->
            <!--              <font-awesome-icon :icon="['fa', 'star']" />-->
            <!--            </div>-->
            <div class="product__header__item__footer">
              <p v-if="labelText">
                {{ labelText }}
              </p>
              <div class="product__header__item__footer__variants">
                <div v-for="variant in this.products[0]" :key="variant.id">
                  <div
                    :class="{ active: variant.isMain == 1 }"
                    class="btn-gray"
                    v-if="variant?.color_id"
                    v-on:click="changeProduct(variant)"
                  >
                    {{ variant.color_name }}
                    <div class="home__eshop__wrapper__img_wrapper__subProducts">
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
                  <template
                    v-if="!products[0][0].color_id && products[0].length > 1"
                  >
                    <div
                      :class="variant.isMain ? 'active' : ''"
                      class="btn-gray"
                      v-if="variant.parent_id !== 0"
                      v-on:click="
                        changeProduct({
                          id: variant.isMain ? 'default' : variant.id,
                        })
                      "
                    >
                      {{ variant.variant }}
                      <font-awesome-icon
                        class="btn-gray__times"
                        v-if="variant.isMain"
                        size="lg"
                        :icon="['fa', 'times']"
                      />
                    </div>
                  </template>
                </div>
              </div>
              <p
                v-if="product.description_sentence"
                class="product__header__item__footer__info"
              >
                {{ product.description_sentence }}
              </p>
              <div
                v-if="
                  product.discount || product.topProduct || product.newProduct
                "
                class="home__eshop__wrapper__discounts"
              >
                <span v-if="product.discount" class="btn-yellow">Akce</span>
                <span v-if="product.topProduct" class="btn-green"
                  >Top produkt</span
                >
                <span v-if="product.newProduct" class="btn-blue">Novinka</span>
              </div>
              <div class="home__eshop__wrapper__price">
                <template v-if="isMainProductActive()">
                  <div class="home__eshop__wrapper__price">
                    <span
                      >{{ this.products[0].length > 1 ? "od" : "" }}
                      <div
                        class="home__eshop__wrapper__price"
                        v-if="getLowestPrice()['discounted']"
                      >
                        <span class="home__eshop__wrapper__price__trough"
                          >{{ getLowestPrice()["originalPrice"] }} Kč</span
                        >

                        <span class="home__eshop__wrapper__price__discount"
                          >{{ getLowestPrice()["price"] }} Kč</span
                        >
                      </div>
                      <template v-else>
                        {{ getLowestPrice()["price"] }} Kč
                      </template>
                    </span>
                  </div>
                </template>
                <template v-if="!isMainProductActive()">
                  <template v-if="product.discounted">
                    <span class="home__eshop__wrapper__price__trough"
                      >{{ product.price }} Kč</span
                    >
                    <span class="home__eshop__wrapper__price__discount"
                      >{{ product.discounted }} Kč</span
                    >
                  </template>
                  <div
                    class="home__eshop__wrapper__price"
                    v-if="!product.discounted"
                  >
                    <span>
                      {{ product.price }}
                      Kč</span
                    >
                  </div>
                </template>
                <div class="product__header__item__footer__buy">
                  <vue-number-input
                    :model-value="1"
                    :min="1"
                    class="center-text"
                    v-model="amount"
                    :inputtable="false"
                    inline
                    controls
                  ></vue-number-input>
                  <button
                    :class="[
                      'btn-yellow',
                      { 'btn-yellow-disabled': isMainProductActive() },
                    ]"
                    @click="addItem(product)"
                    :disabled="isMainProductActive()"
                  >
                    {{
                      isMainProductActive()
                        ? "Vyberte variantu"
                        : "Přidat do košíku"
                    }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>
    <div class="product__description">
      <template v-for="product in products[0]">
        <div
          :key="product.id"
          v-if="product.isMain === 1"
          class="templates__wrapper"
        >
          <template v-for="template in templates" :key="template.id">
            <template-product
              :product="this.parentProduct"
              :template="{ template }"
            />
          </template>
        </div>
      </template>
    </div>
  </div>
  <div v-else>Nebyl nalezen zadaný produkt</div>
</template>

<script>
import axios from "../api";
import ImagesSlider from "../components/ImagesSlider.vue";
import TemplateProduct from "../components/admin/TemplateProduct.vue";

export default {
  components: {
    ImagesSlider,
    TemplateProduct,
    /*CategoryInfo*/
  },
  data() {
    return {
      productPathName: this.$route.params.productPath,
      products: [],
      categories: [],
      amount: 1,
      templates: [],
      parentProduct: null,
      isLoading: true,
      imagesBasePath: `https://be.fitmo.cz/products/`,
    };
  },
  watch: {
    $route() {
      this.productPathName = this.$route.params.productPath;
      this.getCategories();
      this.getProduct().then(() => {
        return this.getTemplates();
      });
    },
  },
  methods: {
    isMainProductActive() {
      if (this.products[0].length === 1) return false;
      const item = this.products[0]?.find((item) => {
        return item?.isMain === 1 && item?.parent_id === 0;
      });
      return item?.color_id ? false : item;
    },
    async getProduct() {
      try {
        const response = await axios.get(
          "/api/product/" + this.productPathName
        );
        if (response.data[0]) {
          this.parentProduct = response.data[0].find(
            (item) => item.parent_id == 0
          );
          // if (
          //   response.data[0][0].price === null &&
          //   response.data[0][0].color_id === null &&
          //   response.data[0].length > 1
          // ) {
          //   // response.data[0].shift();
          //   response.data[0][0].isMain = 1;
          // }
          this.products = response.data;
        }
        this.isLoading = false;
      } catch (error) {
        this.isLoading = false;
        console.log(error);
      }
    },

    getLowestPrice() {
      let lowestDiscounted = null;
      let originalOfDiscounted = null;
      let lowestNormal = null;

      this.products[0]?.forEach((product) => {
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
    async getTemplates() {
      if (this.products[0]) {
        const activeItem = this.products[0].find((item) => {
          return item.isMain === 1;
        });

        try {
          const response = await axios.get(
            `/api/product/${
              this.parentProduct ? this.parentProduct.id : activeItem.id
            }/getTemplates`
          );
          this.templates = response.data.map((item) => {
            item.from = "db";
            return item;
          });
          console.log(this.templates);
        } catch (error) {
          console.log(error);
        }
      }
    },
    addItem(product) {
      const storedItems = JSON.parse(sessionStorage.getItem("cart")) || [];

      const itemToUpdate = storedItems.find(
        (item) => item.id === product.product_id
      );

      if (itemToUpdate) {
        itemToUpdate.count += this.amount;
      } else {
        const newProduct = { ...product, count: this.amount };
        storedItems.push(newProduct);
      }

      this.$store.commit("updateCart", storedItems);

      sessionStorage.setItem("cart", JSON.stringify(storedItems));
    },
    getNavigation(url) {
      let navigation = [];
      let oldElement = "";
      let index = 0;
      if (url && Object.keys(this.categories).length !== 0) {
        url.split("/").forEach((element) => {
          oldElement += "/" + element;
          if (index === 0) {
            oldElement = oldElement.substring(1);
          }
          this.categories.filter((item) => {
            if (item.url_path === oldElement) {
              navigation.push(item);
            }
          });
          index++;
        });
      }
      return navigation;
    },

    changeProduct(variant) {
      if (variant.id === "default") {
        variant.id = this.products[0].find((item) => item.parent_id === 0)?.id;
      }
      this.products[0].forEach((product) => {
        if (product.id != variant.id) {
          product.isMain = 0;
        } else {
          product.isMain = 1;
        }
      });
    },

    async getCategories() {
      const response = await axios.post("/api/categories");
      this.categories = response.data;
    },
  },

  computed: {
    labelText() {
      const product = this.products?.[0];
      if (product?.[0]?.color_id) {
        return "Barva:";
      } else if (product?.length > 1) {
        return "Varianta:";
      } else {
        return "";
      }
    },
  },
  mounted() {
    this.getCategories();
    this.getProduct().then(() => {
      return this.getTemplates();
    });
  },
};
</script>
<style lang="scss">
.product {
  position: relative;

  .home__eshop__wrapper__discounts span {
    font-size: 1.3rem;
  }

  .category__header__navigation {
    border: 0;

    li a,
    svg {
      color: $gray-second;
    }
  }

  .home__eshop__wrapper__item_discount {
    height: 7rem;
    width: 7rem;
    top: 2rem;
    right: 2rem;
    font-size: 1.7rem;
  }

  &__header {
    width: 90%;
    max-width: 1920px;
    margin: auto;
    padding-bottom: 3rem;

    h1 {
      font-size: 3.2rem;
      margin-top: 3rem;
    }

    h1,
    h2,
    h3 {
      text-align: left;
    }

    h2 {
      font-weight: 100;
      color: $gray;
      margin-top: 0.4rem;
      margin-bottom: 0.6rem;
      font-size: 2rem;
    }

    &__rating {
      margin: 1.6rem 0;
    }

    &__item {
      &__img_wrapper {
        margin: auto;
        position: relative;
        width: inherit;

        &__more {
          display: flex;
          gap: 1.6rem;
          margin-top: 1.6rem;

          img {
            //height: 7.5rem;
            //width: 7.5rem;
            border: 1px solid $gray-second;
            padding: 0.6rem;
            aspect-ratio: 3 / 2;
            object-fit: contain;
          }
        }
      }

      &__footer {
        margin-right: auto;
        flex-direction: column;

        &__variants {
          display: flex;
          gap: 0.8rem;
          padding: 1px 0;
          align-items: center;
          flex-wrap: wrap;

          &:has(.active) {
            padding: 0;
          }

          .btn-gray {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            gap: 0.7rem;

            // Hover on .btn-gray changes .btn-gray itself
            &:hover {
              cursor: pointer;
              background: $white;
              color: $black;
              border-color: $yellow;

              // This targets the child .btn-gray__times on hover
              .btn-gray__times {
                background: $gray-second;
                color: $white;
              }
            }

            &.active {
              color: $black;
              border-width: 2px;
              border-color: $yellow;
            }

            &__times {
              position: absolute;
              height: 14px;
              top: -3px;
              right: -3px;
              border: 1px solid $gray-second;
              background: $white;
              border-radius: 50%;
              z-index: 2;
              color: $gray-second;
              transition: 0.2s ease all;
              aspect-ratio: 1/1;

              &:hover {
                background: $gray-second;
                color: $white;
              }
            }
          }

          .home__eshop__wrapper__img_wrapper__subProducts {
            width: 1.5rem;
            height: 1.5rem;
            margin-bottom: 0;
            border: 0;

            > div {
              width: 1.5rem;
              height: 1.5rem;
            }
          }
        }

        &__info {
          max-width: 50rem;
          margin: 1rem 0;
          line-height: 2rem;
          text-align: justify;
        }

        &__buy {
          display: flex;
          justify-content: space-between;
          align-items: center;
          gap: 2rem;

          button.btn-yellow {
            padding: 1rem 4rem;
            font-size: 1.8rem;
          }
        }

        .home__eshop__wrapper {
          &__price {
            display: flex;
            font-size: 2.8rem;
            gap: 0.8rem;
            margin-top: 2rem;
            align-items: flex-start;

            &__trough {
              font-size: 1.9rem;
            }
          }

          &__discounts {
            justify-content: flex-start;
          }
        }
      }

      .home__eshop__wrapper {
        &__price {
        }

        &__discounts span {
          padding: 0.6rem 2rem;
        }
      }
    }
  }

  &__description {
    padding: 7rem 5%;
    background: $gray-third;
    margin: auto;
    text-align: center;

    h2 {
      text-align: left;
      padding: 3rem 0 2rem 0;
    }

    &__info {
      width: 100%;
      max-width: 35rem;

      & > div {
        display: flex;
        justify-content: space-between;
      }
    }
  }
}

.product {
  &__header__item {
    position: relative;
    //max-width: 41rem;
    padding-bottom: 7rem;

    &__footer {
      display: flex;
      margin-top: 0.4rem;
      flex-wrap: wrap;
      width: 100%;

      > p {
        margin-bottom: 0.3rem;
        font-size: 1.6rem;
      }

      &__info {
        display: none;
      }

      .home__eshop__wrapper {
        &__price {
          flex-direction: column;
        }

        &__discounts {
          width: 100%;
        }
      }

      &__buy {
        button.btn-yellow {
          padding: 1.2rem 4rem;
          border-radius: 2.5rem;
          bottom: 0;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .product__header__item {
    &__img_wrapper {
      width: 70%;
      padding-right: 3rem;
    }

    &__footer {
      .home__eshop__wrapper__price {
        justify-content: flex-start;
      }
    }

    &__header {
      width: 30%;
    }
  }
}

@media screen and (min-width: $screen-xl-min) {
}
</style>
