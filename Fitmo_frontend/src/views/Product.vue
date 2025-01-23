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
          <div class="product__header__item__header">
            <h1 v-if="!product.color_name">{{ product.name }}</h1>
            <h1 v-if="product.color_name">
              {{ product.name }}, {{ product.color_name }}
            </h1>
            <h2>{{ product.description }}</h2>
            <div class="product__header__rating">
              <font-awesome-icon :icon="['fa', 'star']" />
              <font-awesome-icon :icon="['fa', 'star']" />
              <font-awesome-icon :icon="['fa', 'star']" />
              <font-awesome-icon :icon="['fa', 'star']" />
              <font-awesome-icon :icon="['fa', 'star']" />
            </div>
          </div>
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

          <div class="product__header__item__footer">
            <div class="product__header__item__footer__variants">
              <div v-for="variant in this.products[0]" :key="variant.id">
                <div
                  class="home__eshop__wrapper__img_wrapper__subProducts"
                  :class="{ active: variant.isMain == 1 }"
                  v-on:click="changeProduct(variant)"
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
              <select
                v-if="!products[0][0].color_id && products[0].length > 1"
                @change="changeProduct({ id: $event.target.value })"
              >
                <template v-for="variant in products[0]">
                  <option
                    v-if="variant.id"
                    :key="variant.id"
                    :value="variant.id"
                    :selected="variant.isMain === 1"
                  >
                    {{ variant.variant }}
                  </option>
                </template>
              </select>
            </div>

            <p class="product__header__item__footer__info">
              {{ product.description_sentence }}
            </p>
            <div class="home__eshop__wrapper__discounts">
              <span v-if="product.discount" class="btn-yellow">Akce</span>
              <span v-if="product.topProduct" class="btn-green"
                >Top produkt</span
              >
              <span v-if="product.newProduct" class="btn-blue">Novinka</span>
            </div>
            <div v-if="product.discounted" class="home__eshop__wrapper__price">
              <span class="home__eshop__wrapper__price__trough"
                >{{ product.price }} Kč</span
              >
              <span class="home__eshop__wrapper__price__discount"
                >{{ product.discounted }} Kč</span
              >
            </div>
            <div v-if="!product.discounted" class="home__eshop__wrapper__price">
              <span>{{ product.price }} Kč</span>
            </div>
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
              <button class="btn-yellow" @click="addItem(product)">
                Přidat do košíku
              </button>
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
    async getProduct() {
      try {
        const response = await axios.get(
          "/api/product/" + this.productPathName
        );
        if (response.data[0]) {
          this.parentProduct = response.data[0].find(
            (item) => item.parent_id == 0
          );
          if (
            response.data[0][0].price === null &&
            response.data[0][0].color_id === null &&
            response.data[0].length > 1
          ) {
            response.data[0].shift();
            response.data[0][0].isMain = 1;
          }
          this.products = response.data;
        }
        this.isLoading = false;
      } catch (error) {
        this.isLoading = false;
        console.log(error);
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

  computed: {},
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
    margin: auto;
    padding-bottom: 3rem;

    h1 {
      font-size: 44px;
      margin-top: 2rem;
    }

    h1,
    h2,
    h3 {
      text-align: left;
    }

    h2 {
      font-weight: 100;
      color: $black;
      font-size: 2.2rem;
    }

    &__rating {
      margin: 1.6rem 0;
    }

    &__item {
      display: grid !important;
      grid-auto-flow: my-row;
      margin: auto;
      column-gap: 3rem;

      &__img_wrapper {
        margin: auto;
        position: relative;
        width: inherit;

        &__more {
          display: flex;
          gap: 1.3rem;
          margin-top: 1.6rem;

          img {
            height: 7.5rem;
            width: 7.5rem;
            border: 1px solid black;
            padding: 0.6rem;
            aspect-ratio: 3 / 2;
            object-fit: contain;
          }
        }
      }

      &__footer {
        margin-right: auto;

        &__variants {
          display: flex;
          gap: 0.8rem;

          .home__eshop__wrapper__img_wrapper__subProducts {
            width: 2.5rem;
            height: 2.5rem;

            > div {
              width: 2.5rem;
              height: 2.5rem;
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
          margin-top: 2rem;
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
          margin-top: 2rem;
        }

        &__discounts span {
          padding: 0.6rem 2rem;
        }
      }
    }
  }

  &__description {
    padding: 7rem 8%;
    background: $gray-third;
    margin: auto;
    text-align: justify;

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

@media screen and (max-width: ($screen-md-min - 1px)) {
  .product {
    &__header__item {
      position: relative;
      //max-width: 41rem;
      padding-bottom: 7rem;

      &__footer {
        display: flex;
        margin-top: 2rem;
        flex-wrap: wrap;
        width: 100%;

        &__info {
          display: none;
        }

        .home__eshop__wrapper {
          &__price {
            order: 2;
            flex-direction: column;
          }

          &__discounts {
            order: 1;
            width: 100%;
          }
        }

        &__buy {
          order: 3;
          margin-left: auto;
          margin-top: 4rem;
          margin-right: 5%;

          button.btn-yellow {
            position: absolute;
            padding: 1.2rem 4rem;
            border-radius: 2.5rem;
            bottom: 0;
            left: calc(50% - 10.9rem);
          }
        }
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .product {
    &__header__item {
      &__img_wrapper {
        grid-column: 1 / span 1;
        grid-my-row: 1 / span 3;
        overflow: hidden;
      }

      &__footer {
        grid-column: 2 / span 1;
        grid-my-row: 2 / span 1;
      }

      &__header {
        margin-top: 6rem;
      }
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .product__header {
    &__item {
      grid-template-columns: 700px 1fr;

      &__footer {
        width: 100%;
      }
    }
  }
}
</style>
