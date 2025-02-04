<template>
  <div class="category searchResult">
    <div class="category__header">
      <h1>Výsledek hledání: {{ this.search }}</h1>
      <div class="category__header__list my-row">
        <div
          v-for="subCategory in this.searchResult.categories"
          :key="subCategory.id"
          class="col-6-xs col-3-sm"
        >
          <router-link
            :to="{
              name: 'Category',
              params: { categoryname: subCategory.path.split('/') },
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
      <div class="category__eshop__filters__order col-12-xs">
        <ul>
          <li>nejlevnější</li>
          <li>nejdražší</li>
          <li>abecedně</li>
          <li>nejprodávanější</li>
        </ul>
      </div>
      <div
        v-if="this.searchResult.products?.length !== 0"
        class="home__eshop__wrapper my-row col-12-xs"
      >
        <Product
          v-for="product in this.searchResult?.products"
          :key="product.id"
          sizes="col-12-xs col-6-sm col-4-lg col-3-xl"
          :products="product"
        />
      </div>
      <div
        v-if="this.searchResult.products?.length === 0"
        class="home__eshop__wrapper my-row col-12-xs col-8-md col-9-lg"
      >
        Pro tuto kategorii nejsou žádné produkty.
      </div>
    </div>
    <div class="category__eshop__pagination">
      <button
        v-if="this.pagination.current_page < this.pagination.last_page"
        class="btn-yellow"
        :onClick="() => this.getCategoryProducts()"
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

          <span class="active" v-if="this.pagination.current_page">{{
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
import axios from "../api";
import Product from "@/components/Product.vue";
import CustomMade from "@/components/CustomMade.vue";
import { VaProgressCircle } from "vuestic-ui";

export default {
  components: {
    VaProgressCircle,
    Product,
    CustomMade,
    /*CategoryInfo*/
  },
  data() {
    return {
      mainCategory: "",
      subCategories: [],
      provizorniCheck: false,
      showFilters: false,
      barMinValue: 10,
      barMaxValue: 5000,
      categoryText: "",
      searchResult: [],
      isLoading: false,
      search: this.$route.params.search,
      pagination: {},
      products: [],
      imageBasePath: `https://be.fitmo.cz/categories/`,
    };
  },

  mounted() {
    // Run the search logic on page load (refresh or initial load)
    this.getSearchResult();
  },
  watch: {
    $route(to, from) {
      if (to.params.search !== from.params.search) {
        // Update search and reset products on route change
        this.search = to.params.search;
        this.pagination = {};
        this.products = [];
        this.getSearchResult();
      }
    },
  },

  methods: {
    async getSearchResult() {
      try {
        const response = await axios.get(`/api/search/${this.search}`);
        this.searchResult = response.data;
        console.log(response.data);
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>
<style lang="scss"></style>
