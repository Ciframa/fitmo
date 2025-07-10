<template>
  <div class="category">
    <!-- Modal -->
    <div
      class="modal fade"
      id="filterModal"
      tabindex="-1"
      aria-labelledby="filterModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-slideout modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="filterModalLabel">Filtry</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="category__eshop__filters">
              <div class="category__eshop__filters__price">
                <span>Cena</span>
                <MultiRangeSlider
                  v-if="
                    initFilters.price.min !== null &&
                    initFilters.price.max !== null
                  "
                  baseClassName="multi-range-slider"
                  :min="initFilters.price.min"
                  :max="initFilters.price.max"
                  :step="1"
                  :ruler="false"
                  :label="true"
                  :labels="[barMinValue, barMaxValue]"
                  :minValue="barMinValue"
                  :maxValue="barMaxValue"
                  @input="UpdateValues"
                />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Zavřít
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="category__header">
      <ul class="category__header__navigation">
        <li>
          <router-link :to="'/'">
            <font-awesome-icon :icon="['fa', 'house']" />
          </router-link>
          <font-awesome-icon :icon="['fa', 'angle-down']" rotation="270" />
        </li>

        <li
          v-for="(link, index) in this.getNavigation(
            this.mainCategory.url_path
          )"
          :key="link.id"
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
      </ul>
      <h1>{{ mainCategory.name }}</h1>
      <article>
        <p>{{ categoryText }}</p>
      </article>
      <div class="category__header__list my-row">
        <div
          v-for="subCategory in subCategories"
          :key="subCategory.id"
          class="col-6-xs col-3-sm"
        >
          <router-link
            :to="{
              name: 'Category',
              params: { categoryname: subCategory.url_path.split('/') },
            }"
          >
            <div class="category__header__list__item">
              <span>{{ subCategory.name }}</span>
              <img :src="imageBasePath + subCategory.image_path" alt="" />
            </div>
          </router-link>
        </div>
      </div>
    </div>
    <div class="category__eshop my-row">
      <div class="banners-centered">
        <img src="../../public/assets/banners/druhy.png" alt="" />
      </div>
      <div class="category__eshop__filters__order col-12-xs">
        <ul>
          <li>nejlevnější</li>
          <li>nejdražší</li>
          <li>abecedně</li>
          <li>nejprodávanější</li>
        </ul>
        <button
          type="submit"
          data-bs-toggle="modal"
          data-bs-target="#filterModal"
          class="btn-gray"
        >
          Zobrazit filtry
          <font-awesome-icon :icon="['fa', 'filter']" />
        </button>
      </div>
      <!--      <div class="category__eshop__filters col-12-xs col-4-md col-3-lg">-->
      <!--        <div class="category__eshop__filters__price">-->
      <!--          <span>Cena</span>-->
      <!--          <MultiRangeSlider-->
      <!--            baseClassName="multi-range-slider"-->
      <!--            :min="0"-->
      <!--            :max="100"-->
      <!--            :step="1"-->
      <!--            :ruler="false"-->
      <!--            :label="true"-->
      <!--            :labels="[barMinValue, barMaxValue]"-->
      <!--            :minValue="barMinValue"-->
      <!--            :maxValue="barMaxValue"-->
      <!--            @input="UpdateValues"-->
      <!--          />-->
      <!--        </div>-->
      <!--        <button class="btn-gray" v-on:click="showFilters = !showFilters">-->
      <!--          zobrazit další filtry-->
      <!--        </button>-->
      <!--        <div-->
      <!--          class="category__eshop__filters__brands"-->
      <!--          :class="{-->
      <!--            show: showFilters,-->
      <!--          }"-->
      <!--        >-->
      <!--          <span>Značky</span>-->
      <!--          <div class="category__eshop__filters__brands__list">-->
      <!--            <ul>-->
      <!--              <li v-on:click="provizorniCheck = !provizorniCheck">-->
      <!--                <font-awesome-icon-->
      <!--                  :icon="['fa', 'check']"-->
      <!--                  :class="{-->
      <!--                    show: provizorniCheck,-->
      <!--                  }"-->
      <!--                />-->
      <!--                <span>Fitmo</span>-->
      <!--              </li>-->
      <!--              <li v-on:click="provizorniCheck = !provizorniCheck">-->
      <!--                <font-awesome-icon-->
      <!--                  :icon="['fa', 'check']"-->
      <!--                  size="2x"-->
      <!--                  :class="{-->
      <!--                    show: provizorniCheck,-->
      <!--                  }"-->
      <!--                />-->
      <!--                <span>Fitmo</span>-->
      <!--              </li>-->
      <!--              <li v-on:click="provizorniCheck = !provizorniCheck">-->
      <!--                <font-awesome-icon-->
      <!--                  :icon="['fa', 'check']"-->
      <!--                  size="2x"-->
      <!--                  :class="{-->
      <!--                    show: provizorniCheck,-->
      <!--                  }"-->
      <!--                />-->
      <!--                <span>Fitmo</span>-->
      <!--              </li>-->
      <!--              <li v-on:click="provizorniCheck = !provizorniCheck">-->
      <!--                <font-awesome-icon-->
      <!--                  :icon="['fa', 'check']"-->
      <!--                  size="2x"-->
      <!--                  :class="{-->
      <!--                    show: provizorniCheck,-->
      <!--                  }"-->
      <!--                />-->
      <!--                <span>Fitmo</span>-->
      <!--              </li>-->
      <!--              <li v-on:click="provizorniCheck = !provizorniCheck">-->
      <!--                <font-awesome-icon-->
      <!--                  :icon="['fa', 'check']"-->
      <!--                  size="2x"-->
      <!--                  :class="{-->
      <!--                    show: provizorniCheck,-->
      <!--                  }"-->
      <!--                />-->
      <!--                <span>Fitmo</span>-->
      <!--              </li>-->
      <!--              <li>-->
      <!--                <font-awesome-icon :icon="['fa', 'times']" size="2x" />-->
      <!--                <span>Zrušit všechny filtry</span>-->
      <!--              </li>-->
      <!--            </ul>-->
      <!--          </div>-->
      <!--        </div>-->
      <!--        <div class="category__eshop__filters__info">-->
      <!--          <h4>Poradíme <br />Vám s výběrem</h4>-->
      <!--          <div class="category__eshop__filters__info__wrapper">-->
      <!--            <a href="tel:+420 702 064 265">+420 702 064 265</a>-->
      <!--            <span>po-pá: 8:00 - 17:00</span>-->
      <!--          </div>-->
      <!--          <div class="category__eshop__filters__info__wrapper">-->
      <!--            <a target="_blank" href="mailto:obchod@fitmo.cz">obchod@fitmo.cz</a>-->
      <!--          </div>-->
      <!--          <span>Kontaktujte nás</span>-->
      <!--        </div>-->
      <!--        <img src="../../public/assets/banners/treti.png" alt="" />-->
      <!--        <img src="../../public/assets/banners/treti.png" alt="" />-->
      <!--      </div>-->
      <VaProgressCircle v-show="this.wholeProductsAreLoading" indeterminate />
      <div
        v-if="this.products.length !== 0 && !this.wholeProductsAreLoading"
        class="home__eshop__wrapper my-row col-12-xs"
      >
        <Product
          v-for="product in this.products"
          :key="product.id"
          sizes="col-12-xs col-6-sm col-4-lg col-3-xl"
          :products="product"
        />
      </div>
      <div
        v-if="this.products.length === 0"
        class="home__eshop__wrapper my-row col-12-xs col-8-md col-9-lg"
      >
        Pro tuto kategorii nejsou žádné produkty.
      </div>
    </div>
    <div class="category__eshop__pagination">
      <button
        v-if="this.pagination.current_page < this.pagination.last_page"
        class="btn-yellow"
        :onClick="() => this.getCategoryProducts(this.pagination.next_page_url)"
      >
        <VaProgressCircle v-show="this.isLoading" indeterminate />
        <span v-show="!this.isLoading">Zobrazit další produkty</span>
      </button>
      <div class="category__eshop__pagination__pages">
        <button
          class="btn"
          :onClick="
            () => this.getCategoryProducts(this.pagination.prev_page_url)
          "
          v-if="this.pagination.current_page > 1"
        >
          <font-awesome-icon :size="'2x'" :icon="['fas', 'angle-left']" />
        </button>
        <div class="category__eshop__pagination__pages__list">
          <template v-if="this.pagination.current_page - 2 > 1">
            <span
              v-if="this.pagination.current_page - 2 > 1"
              :onClick="
                () => this.getCategoryProducts(`${this.pagination.path}?page=1`)
              "
            >
              1
            </span>
            <p v-if="this.pagination.current_page - 3 > 1">...</p>
          </template>
          <span
            v-if="this.pagination.current_page > 2"
            :onClick="
              () =>
                this.getCategoryProducts(
                  `${this.pagination.path}?page=${
                    this.pagination.current_page - 2
                  }`
                )
            "
            >{{ this.pagination.current_page - 2 }}</span
          >
          <span
            v-if="this.pagination.current_page > 1"
            :onClick="
              () =>
                this.getCategoryProducts(
                  `${this.pagination.path}?page=${
                    this.pagination.current_page - 1
                  }`
                )
            "
            >{{ this.pagination.current_page - 1 }}</span
          >

          <span v-if="this.pagination.current_page" class="active">{{
            this.pagination.current_page
          }}</span>

          <span
            v-if="this.pagination.current_page + 1 < this.pagination.last_page"
            :onClick="
              () =>
                this.getCategoryProducts(
                  `${this.pagination.path}?page=${
                    this.pagination.current_page + 1
                  }`
                )
            "
            >{{ this.pagination.current_page + 1 }}</span
          >
          <span
            v-if="this.pagination.current_page + 2 < this.pagination.last_page"
            :onClick="
              () =>
                this.getCategoryProducts(
                  `${this.pagination.path}?page=${
                    this.pagination.current_page + 2
                  }`
                )
            "
            >{{ this.pagination.current_page + 2 }}</span
          >

          <p
            v-if="this.pagination.current_page + 3 < this.pagination.last_page"
          >
            ...
          </p>
          <span
            v-if="this.pagination.current_page + 1 <= this.pagination.last_page"
            :onClick="
              () =>
                this.getCategoryProducts(
                  `${this.pagination.path}?page=${this.pagination.last_page}`
                )
            "
            >{{ this.pagination.last_page }}</span
          >
        </div>
        <button
          class="btn"
          :onClick="
            () => this.getCategoryProducts(this.pagination.next_page_url)
          "
          v-if="
            this.pagination.current_page !== this.pagination.last_page &&
            this.pagination.last_page > 1
          "
        >
          <font-awesome-icon :size="'2x'" :icon="['fas', 'angle-right']" />
        </button>
      </div>
    </div>

    <div class="home__gray_banner"></div>
    <CustomMade />
  </div>
</template>

<script>
//import CategoryInfo from "../components/CategoryInfo.vue";
import MultiRangeSlider from "multi-range-slider-vue";
import axios from "../api";
import Product from "@/components/Product.vue";
import CustomMade from "@/components/CustomMade.vue";

export default {
  components: {
    MultiRangeSlider,
    Product,
    CustomMade,
    /*CategoryInfo*/
  },
  data() {
    return {
      initFilters: {
        price: {
          min: null,
          max: null,
        },
      },
      url_path: this.$route.params.categoryname,
      mainCategory: "",
      subCategories: [],
      products: [],
      provizorniCheck: false,
      showFilters: false,
      barMinValue: 0,
      barMaxValue: 0,
      categoryText: "",
      isLoading: false,
      wholeProductsAreLoading: false,
      pagination: {},
      categories: [],
      imageBasePath: `https://be.fitmo.cz/categories/`,
    };
  },
  watch: {
    $route() {
      this.url_path = this.$route.params.categoryname;
      this.pagination = {};
      this.products = [];
      this.getSubCategories();
      this.getCategoryProducts();
      this.getInitFilters();
    },
  },

  methods: {
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
    async getCategories() {
      const response = await axios.post("/api/categories");
      this.categories = response.data;
    },
    async getSubCategories() {
      try {
        const response = await axios.get("/api/category/" + [this.url_path]);
        this.subCategories = response.data;
        this.mainCategory = this.subCategories.pop();
        if (this.mainCategory.url_path === "ocr-vybaveni") {
          this.categoryText =
            "U nás si vybereš vše, co budeš pro trénink na OCR závody potřebovat. Veškeré segmenty si vyrábíme sami a to v té nejlepší kvalitě. Mrkni na naši nabídku a určitě si vybereš právě to vybavení, se kterým už ti v tréninku nebude nic chybět!";
        } else if (this.mainCategory.url_path === "fitness-vybaveni") {
          this.categoryText =
            "Chybí ti nějaký doplněk pro tvůj workout? Rozhodnul jses začít pořádně makat? Tak to jsi tu správně! Mrkni na naši nabídku a určitě najdeš co potřebuješ.";
        } else if (this.mainCategory.url_path === "regenerace") {
          this.categoryText =
            "Bez správné regenerace to ve sportu prostě nejde a jestli se chceš vyhnout zranění a ještě mít výsledky, tak jsi tu správně!";
        } else if (this.mainCategory.url_path === "vyziva") {
          this.categoryText =
            "Ve spolupráci s prémiovou českou značkou EDGAR POWER ti přinášíme produkty, které tě povedou k neobyčejným výsledkům. ";
        } else {
          this.categoryText = "";
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getCategoryProducts(requestedUrl = null) {
      this.isLoading = true;
      let shouldReset = true;
      if (requestedUrl) {
        const urlPage = requestedUrl.split("?page=")[1];
        shouldReset =
          !urlPage ||
          urlPage < this.pagination.current_page ||
          urlPage - this.pagination.current_page > 1;
      }
      try {
        const url =
          requestedUrl ??
          this.pagination.next_page_url ??
          "/api/categoryProducts/" + [this.url_path];
        const response = await axios.post(url, {
          filter: {
            price: {
              min: this.barMinValue,
              max: this.barMaxValue,
            },
          },
        });
        if (this.products.length === 0) {
          shouldReset = false;
        }
        if (shouldReset) {
          this.wholeProductsAreLoading = true;
          this.products = [];
          this.$nextTick(() => {
            const element = document.querySelector(
              ".category__eshop__filters__order"
            );
            if (element) {
              element.scrollIntoView({ behavior: "smooth" });
            }
          });
        }
        this.products = [...this.products, ...response.data.data];
        this.pagination = response.data.pagination;
      } catch (error) {
        console.log(error);
      } finally {
        this.isLoading = false;
        this.wholeProductsAreLoading = false;
      }
    },

    async getInitFilters() {
      try {
        const response = await axios.get("/api/initFilters/" + [this.url_path]);
        this.initFilters = {
          ...this.initFilters,
          price: {
            min: response.data.minPrice,
            max: response.data.maxPrice,
          },
        };
        this.barMinValue = response.data.minPrice;
        this.barMaxValue = response.data.maxPrice;
      } catch (error) {
        console.log(error);
      }
    },

    UpdateValues(e) {
      this.barMinValue = e.minValue;
      this.barMaxValue = e.maxValue;

      // here have some debounce for like 300ms
      // and then call getCategoryProducts
      if (this.debouncedGetCategoryProducts) {
        clearTimeout(this.debouncedGetCategoryProducts);
      }
      this.debouncedGetCategoryProducts = setTimeout(() => {
        this.wholeProductsAreLoading = true;
        this.getCategoryProducts(this.pagination.path);
      }, 300);
    },
  },
  mounted() {
    this.getInitFilters().then(() => this.getCategoryProducts());
    this.getSubCategories();
    this.getCategories();
  },
};
</script>
<style lang="scss">
.banners-centered {
  margin: auto;
}

.category {
  background: $gray-third;
  position: relative;

  &__header {
    max-width: 1140px;
    width: 90%;
    text-align: center;
    margin: auto;

    &__navigation {
      display: flex;
      flex-wrap: wrap;
      margin: auto;
      column-gap: 0.8rem;
      color: $gray;
      width: 90%;
      border-top: 1px solid $gray-second;
      padding-top: 1.2rem;
      font-weight: 500;

      li {
        gap: 0.8rem;
        display: flex;
        justify-content: center;
        align-items: center;

        a {
          color: $gray;
        }
      }
    }

    h1 {
      font-size: 3.5rem;
      font-weight: 700;
      line-height: 3.3rem;
      text-align: center;
    }

    article {
      width: 90%;
      margin: auto;
      text-align: center;
      font-size: 1.5rem;
      line-height: 1.3em;
      margin-top: 3rem;
      max-width: 85rem;

      p {
        font-size: 1.6rem;
        line-height: 2.2rem;
      }

      p:last-child {
        font-weight: 600;
        margin-top: 2rem;
        font-size: 1.6rem;
        color: #393636;
      }
    }

    &__list {
      padding: 5rem 0;
      justify-content: center;

      & > div {
        padding: 0.7rem;
        display: flex;
        min-width: 240px;
        justify-content: center;

        & > a {
          width: 100%;

          img {
            height: 9rem;
            padding: 0.3rem 2rem 0.3rem 0.8rem;
          }

          span {
            font-weight: 700;
            padding-left: 3rem;
            text-align: left;
            margin-right: auto;
          }
        }
      }

      &__item {
        display: flex;
        align-items: center;
        justify-content: center;
        background: $white;
        border-radius: 2rem;
        width: 100%;
      }
    }
  }

  &__eshop {
    align-items: flex-start;
    background: $white;
    padding: 6rem 5% 2rem 5%;
    max-width: 1920px;
    margin: auto;
    display: flex;
    box-shadow: 0 0 0 100vmax white;
    clip-path: inset(0 -100vmax);
    justify-content: space-between;

    h4 {
    }

    &__pagination {
      display: flex;
      width: 100%;
      justify-content: flex-end;
      align-items: center;
      position: relative;
      height: 60px;
      background: $white;
      padding-bottom: 14rem;

      .btn-yellow {
        //  color: #525358;
        color: $black-headers;
        padding: 1rem 2rem !important;
        font-weight: 500;
        //  border-radius: 0 0 2rem 2rem;
        //  font-size: 12px;
        //  text-decoration: underline;
        //  border: none;
      }

      &__pages {
        position: absolute;
        left: 50%;
        right: 50%;
        top: 7rem;
        display: flex;
        justify-content: center;
        align-items: center;

        svg {
          color: $black-headers;
        }

        &__list {
          display: flex;
          justify-content: center;
          align-items: center;
          gap: 0.4rem;
          margin-bottom: 2px;

          p {
            user-select: none;
          }

          span {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 28px;
            aspect-ratio: 1/1;
            text-decoration: underline;
            border-radius: 50%;
            height: 100%;
            cursor: pointer;

            &:hover {
              transition: 0.3s ease all;
              font-weight: 500;
            }

            &.active {
              font-weight: 500;
              background: $yellow;
              color: $black-headers;
              border-color: $yellow;
              text-decoration: none;
              user-select: none;
              cursor: auto;
            }
          }
        }
      }
    }

    & .banners-centered > img {
      -webkit-box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.33);
      -moz-box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.33);
      box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.33);
      border-radius: 2.5rem;
    }

    &__filters {
      padding-right: 1rem;

      img {
        -webkit-box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.33);
        -moz-box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.33);
        box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.33);
        border-radius: 2.5rem;
        margin: 1rem 0;
      }

      button {
        display: flex;
        justify-content: center;
        margin: 2rem auto;
        padding: 1rem 3rem;
      }

      &__order {
        text-align: center;
        align-items: center;

        span {
          font-size: 2rem;
        }

        ul li {
          font-size: 1.6rem;
        }
      }

      &__price,
      &__brands {
        //background: $gray-third;
        display: flex;
        flex-direction: column;
        padding: 3rem 3rem;
        border-radius: 2rem;
        margin-bottom: 1rem;

        & > span {
          font-size: 1.7rem;
          margin: 0.7rem 0 1.4rem 0;
        }
      }

      &__price {
        .multi-range-slider {
          padding: 0.5rem 0;
          border: none;
          box-shadow: none;

          .bar-left,
          .bar-right {
            //background: $gray-second;
            box-shadow: none;
            height: 6px !important;
            padding: 0;
          }

          .bar {
            height: 6px;
          }

          .bar-inner {
            background-color: $yellow;
            box-shadow: none;
            border: none;
          }

          .thumb::before {
            border: none;
            width: 2.3rem;
            height: 2.3rem;
            border-radius: 0.8rem;
            -webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.25);
            -moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.25);
            box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.25);
          }

          .caption > * {
            display: none;
          }

          .labels {
            font-size: 1.5rem;
            margin-top: 1.4rem;
          }

          .label::after {
            content: "Kč";
            display: block;
            margin-left: 0.3rem;
          }
        }
      }

      &__brands {
        padding-top: 1rem;

        ul {
          display: flex;
          gap: 0.6rem;
          flex-direction: column;

          li {
            &:last-of-type {
              display: flex;
              align-items: center;
              gap: 1rem;
              padding-left: 0.3rem;
              margin-top: 0.7rem;

              svg {
                font-size: 2rem;
              }
            }

            &:not(:last-of-type) {
              span {
                margin-left: 0.8rem;
              }

              svg {
                background: $white;
                border-radius: 0.8rem;
                color: $white;
                height: 1.8rem;
                padding: 0.25rem;

                &.show {
                  background: $yellow;
                }
              }
            }
          }

          &.show {
            display: flex;
          }
        }
      }

      &__info {
        background: $black-second;
        border-radius: 2rem;
        padding: 2.5rem;
        text-align: center;
        color: $white;

        a,
        h4 {
          color: $white;
        }

        > :nth-child(2)::after {
          content: "";
          position: absolute;
          bottom: 0;
          display: block;
          width: 80%;
          height: 2px;
          background: $white;
          left: 10%;
        }

        &__wrapper {
          position: relative;
          padding: 1.5rem;

          a {
            color: $yellow;
          }
        }
      }
    }
  }
}

@media screen and (max-width: $screen-sm-min - 1px) {
  .category {
    &__eshop {
      .home__eshop__wrapper {
        & > div::after {
          display: none;
        }
      }
    }
  }
}

@media screen and (max-width: $screen-md-min - 1px) {
  .category {
    &__eshop {
      &__filters__brands {
        display: none;
      }

      & > img {
        display: none;
      }

      &__filters {
        &__info,
        img {
          display: none;
        }
      }
    }

    &__header__list > div {
      &:nth-child(odd) {
        justify-content: end;
      }

      &:nth-child(even) {
        justify-content: flex-start;
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) and (max-width: $screen-lg-min - 1px) {
  .category__header__list {
    & > div {
      /*&:nth-child(3n) {
        padding-right: 0;
      }

      &:nth-child(3n + 1) {
        padding-left: 0;
      }*/
    }
  }
}

@media screen and (min-width: $screen-sm-min) {
  .category__header__list {
    & > div {
      img {
        height: 10rem;
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .category {
    &__header__list {
      & > div {
        img {
          height: 8rem;
        }
      }
    }

    &__eshop__filters {
      button {
        display: none;
      }

      &__order {
        display: flex;
        margin-left: auto;
        padding-top: 8rem;
        font-size: 1.4rem;

        span {
          width: 20%;
        }

        ul {
          display: flex;
          width: 66%;
          margin-left: auto;
          justify-content: space-evenly;
          margin-bottom: 1rem;

          li {
            text-decoration: underline;
          }
        }
      }

      &__price {
        margin-bottom: 0;
        border-radius: 2rem 2rem 0 0;
        position: relative;

        //&::after {
        //  content: "";
        //  height: 2px;
        //  width: calc(100% - 6rem);
        //  background: $gray-second;
        //  position: absolute;
        //  bottom: 0;
        //}
      }

      &__brands {
        border-radius: 0 0 2rem 2rem;
      }
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .category__eshop {
    &__pagination {
      padding-bottom: 9rem;

      &__pages {
        left: unset;
        right: 5%;
        top: 0;
      }
    }

    &__filters__order ul {
      width: 75%;
    }
  }
}

@media screen and (max-width: $screen-xl-min - 1px) {
  .category__header__list__item {
    max-width: 24rem;
  }
}

@media screen and (min-width: $screen-xl-min) {
  .category__header__list {
    & > div {
      width: 20%;

      img {
        height: 10rem;
      }
    }
  }
}

.modal-dialog-slideout {
  position: fixed;
  right: 0;
  margin: 0;
  height: 100vh;
  max-width: 400px; /* Or whatever width you want */
  min-width: 400px; /* Or whatever width you want */

  .modal-content {
    height: 100vh;
    border-radius: 0;
    border: none;
    box-shadow: none;
  }
}

.modal.fade .modal-dialog-slideout {
  transform: translateX(100%);
  transition: transform 0.3s ease-out;
}

.modal.fade.show .modal-dialog-slideout {
  transform: translateX(0);
}
</style>
