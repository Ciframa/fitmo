<template>
  <div class="home">
    <div class="home__header">
      <h2>PŘIPRAVTE SE NA OCR ZÁVODY KOMPLEXNĚ. <br />STAČÍ SI JEN VYBRAT!</h2>
      <div class="my-row">
        <div
          v-for="category in categories"
          :key="category.id"
          class="col-12-xs col-6-md col-3-xl"
        >
          <div class="category_wrapper">
            <img :src="imageBasePath + category.image_path" alt="" />
            <h3>
              <router-link
                :to="{
                  name: 'Category',
                  params: { categoryname: category.url_path },
                }"
              >
                {{ category.name }}
              </router-link>
            </h3>
            <ul>
              <li v-for="child in category.children" :key="child.id">
                <a :href="child.url_path">{{ child.name }}</a>
              </li>
            </ul>
            <router-link
              class="btn-gray"
              :to="{
                name: 'Category',
                params: { categoryname: category.url_path },
              }"
            >
              Všechny produkty
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="banners">
      <!--      <b-carousel-->
      <!--        id="banners-carousel"-->
      <!--        v-model="slide"-->
      <!--        :interval="1000"-->
      <!--        controls-->
      <!--        indicators-->
      <!--        style="text-shadow: 1px 1px 2px #333"-->
      <!--        @sliding-start="onSlideStart"-->
      <!--        @sliding-end="onSlideEnd"-->
      <!--      >-->
      <!--        &lt;!&ndash; Text slides with image &ndash;&gt;-->
      <!--        <b-carousel-slide-->
      <!--          img-src="https://picsum.photos/1024/480/?image=52"-->
      <!--        ></b-carousel-slide>-->
      <!--      </b-carousel>-->
      <img
        class="xs-to-sm"
        src="../../public/assets/banners/zudesign_fitmo_bannery_4pilire_eshop_1_4_576x1200_vyziva.png"
        alt=""
      />
      <img
        src="../../public/assets/banners/zudesign_fitmo_bannery_4pilire_eshop_1_4_768x1200_vyziva.png"
        alt=""
        class="sm-to-md"
      />
      <img
        class="md-to-lg"
        src="../../public/assets/banners/zudesign_fitmo_bannery_4pilire_eshop_1_4_975x1200_vyziva.png"
        alt=""
      />
      <img
        class="lg-to-xl"
        src="../../public/assets/banners/zudesign_fitmo_bannery_4pilire_eshop_1_4_1200x650_vyziva.png"
        alt=""
      />
      <img
        class="xl-to-"
        src="../../public/assets/banners/zudesign_fitmo_bannery_4pilire_eshop_1_4_1920x750_vyziva.png"
        alt=""
      />
    </div>
    <div class="home__trailer">
      <div class="my-row">
        <div class="col-12-xs col-6-md col-3-xl">
          <img src="../../public/assets/showRoom/prvni.jpg" alt="" />
          <div class="img_text_wrapper">
            <img src="../../public/assets/icons/quality.svg" alt="" />
            <span>KVALITA ZBOŽÍ</span>
          </div>
        </div>
        <div class="col-12-xs col-6-md col-3-xl">
          <img src="../../public/assets/showRoom/druhy.jpg" alt="" />

          <div class="img_text_wrapper">
            <img src="../../public/assets/icons/equipment.svg" alt="" />
            <span>VYBAVENÍ DO GYMŮ</span>
          </div>
        </div>
        <div class="col-12-xs col-6-md col-3-xl">
          <img src="../../public/assets/showRoom/treti.jpg" alt="" />
          <div class="img_text_wrapper">
            <img src="../../public/assets/icons/one_place.svg" alt="" />
            <span>VŠE NA JEDNOM MÍSTĚ</span>
          </div>
        </div>
        <div class="col-12-xs col-6-md col-3-xl">
          <img src="../../public/assets/showRoom/ctvrty.jpg" alt="" />
          <div class="img_text_wrapper">
            <img src="../../public/assets/icons/ocr.svg" alt="" />
            <span>SPECIALISTA NA OCR ZÁVODY!</span>
          </div>
        </div>
      </div>
    </div>
    <div class="home__eshop my-row">
      <h3 class="col-12-xs">E-SHOP</h3>

      <div class="home__eshop__wrapper my-row col-12-xs">
        <Product
          v-for="product in products"
          :key="product.id"
          sizes="col-6-xs col-4-lg col-3-xl"
          :products="product"
        />
      </div>
      <button
        v-if="this.pagination.current_page < this.pagination.last_page"
        class="btn-gray"
        :onClick="() => getProducts()"
      >
        <VaProgressCircle v-show="this.isLoading" indeterminate />
        <span v-show="!this.isLoading">Zobrazit další produkty</span>
      </button>
    </div>
    <div class="home__gray_banner"></div>
    <CustomMade></CustomMade>
    <div class="home__reviews">
      <Slider v-if="ratings.length" :ratings="ratings"></Slider>
    </div>
  </div>
</template>

<script>
import axios from "../api";
import CustomMade from "@/components/CustomMade.vue";
import Slider from "@/components/Slider.vue";
import Product from "@/components/Product.vue";

export default {
  components: {
    CustomMade,
    Slider,
    Product,
  },

  data() {
    return {
      slide: 0,
      sliding: null,
      page: 0,
      categories: [],
      ratings: [],
      products: [],
      pagination: {},
      isLoading: false,
      imageBasePath: `https://be.fitmo.cz/categories/`,
    };
  },

  methods: {
    onSlideStart(slide) {
      this.sliding = true;
    },
    onSlideEnd(slide) {
      this.sliding = false;
    },
    getCategories() {
      axios
        .post("/api/categories", {
          filter: [{ column: "id_parent", operator: "!=", value: null }],
        })
        .then((response) => {
          this.categories = this.groupByKeyReduce(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    groupByKeyReduce(arr) {
      if (arr.length === 0) return;
      var map = {};
      var array = arr;
      for (var i = 0; i < array.length; i++) {
        var obj = array[i];
        obj.children = [];

        map[obj.id] = obj;

        var parent = obj.id_parent || "-";
        if (!map[parent]) {
          map[parent] = {
            children: [],
          };
        }
        map[parent].children.push(obj);
      }

      return map["-"].children;
    },
    async getRatings() {
      try {
        const response = await axios.get("/api/ratings");
        this.ratings = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    async getProducts() {
      this.isLoading = true;
      try {
        const url = this.pagination.next_page_url ?? "/api/products";
        const response = await axios.get(url);
        this.products = [...this.products, ...response.data.data];
        this.pagination = response.data.pagination;
      } catch (error) {
        console.log(error);
      } finally {
        this.isLoading = false;
      }
    },
  },

  mounted() {
    this.getCategories();
    this.getRatings();
    this.getProducts();
  },
};
</script>
<style lang="scss">
.Home {
  max-height: none;
}

.home {
  background: $gray-third;

  &__header {
    max-width: 1140px;
    width: 90%;
    margin: auto;
    padding-top: 3.4rem;

    &__showroom {
      height: 50rem;
      border: 1px solid black;
      margin: 7rem 0;

      img {
        object-fit: cover;
      }
    }

    h2 {
      font-size: 3rem;
    }

    .my-row {
      display: flex;
      margin-top: 5rem;

      > div {
        display: flex;
        justify-content: center;
        padding: 1rem;
        margin: 0;

        .category_wrapper {
          background: $white;
          margin-top: 7rem;
          display: flex;
          border-radius: 2rem;
          flex-direction: column;
          padding: 3rem 0 0 3rem;
          position: relative;
          max-width: 30rem;
          transition: 0.3s ease all;

          &:hover {
            box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.07);
          }

          img {
            max-width: 22rem;
            aspect-ration: 220/180;
            object-fit: contain;
            position: absolute;
            top: 0;
            left: calc(50% - 11rem);
            transform: translateY(-45%);
          }

          h3 {
            max-width: 60%;
            text-align: left;
            margin-top: 7rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            height: 6rem;
            display: flex;
            align-items: flex-end;
          }

          ul {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 3rem;
            overflow: hidden;
            max-height: 7.8rem;

            li {
              padding-right: 0.9rem;
              position: relative;

              a {
                position: relative;

                &::before {
                  content: "";
                  display: block;
                  position: absolute;
                  bottom: 2px;
                  left: 0%;
                  transition: 0.2s ease width;
                  height: 1px;
                  background: $gray-second;
                  width: 0%;
                }

                &:hover {
                  &::before {
                    width: 100%;
                  }
                }
              }

              &::after {
                content: "";
                width: 1px;
                height: 65%;
                background: $gray-second;
                display: block;
                position: absolute;
                top: 17.5%;
                left: -0.5rem;
              }
            }
          }

          & > a {
            width: 18rem;
            margin: auto 0 2rem 0;
          }
        }
      }
    }
  }

  &__banner {
    img {
      width: 100%;
    }

    margin: 4rem 0 4.5rem 0;
  }

  &__trailer {
    color: $white;
    font-weight: 400;
    font-size: 1.8rem;
    position: relative;

    .my-row > div {
      position: relative;

      > img {
        padding: 1rem;
        border-radius: 2.5rem;
        object-fit: cover;
        min-height: 100%;
      }

      .img_text_wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;

        img {
          height: 11rem;
          filter: drop-shadow(2px 4px 16px black);
        }

        span {
          font-weight: 700;
          font-size: 1.6rem;
          filter: drop-shadow(2px 4px 16px black);
        }
      }
    }
  }

  &__eshop {
    max-width: 1520px;
    margin: auto;
    justify-content: space-between;
    padding: 0 5%;
    margin-bottom: 4rem;

    .btn-gray {
      color: #525358;
      padding: 1rem 2rem;
      border-radius: 0 0 2rem 2rem;
      border: none;
      font-size: 12px;
      text-decoration: underline;
    }

    & > img {
      margin: 6rem 0 20rem 0;
    }

    h3 {
      font-size: 3.2rem;
      margin: 13.4rem 0 4rem 0;
    }

    &__wrapper {
      background: $white;
      border-radius: 2rem;
      position: relative;
      padding-bottom: 2rem;

      &::before {
        content: "";
        top: 0;
        left: 2rem;
        width: calc(100% - 4rem);
        height: 1px;
        background: white;
        position: absolute;
        z-index: 2;
      }

      &::after {
        content: "";
        right: 0;
        top: 2rem;
        height: calc(100% - 4rem);
        width: 1px;
        background: white;
        position: absolute;
        z-index: 2;
      }

      &__img_wrapper {
        position: relative;
        width: 100%;
        //height: 100%;
        display: flex;
        align-items: center;
        flex-direction: column;
        overflow: hidden;

        &__subProducts {
          display: flex;
          border-radius: 50%;
          margin-bottom: 0.4rem;
          height: 2.2rem;
          width: 2.2rem;
          border: 2px solid $gray-second;
          overflow: hidden;
          flex-direction: row;

          &__wrapper {
            height: 2.5rem;
            margin-bottom: 0.2rem;
          }

          &.active {
            border-color: $black-headers;
            border-width: 3px;
          }

          & > div {
            display: block;
            height: 2.2rem;
            width: 2.2rem;
          }
        }

        img {
          transition: 0.3s ease all;
          width: 100%;
          height: 100%;
          margin: 0 auto;
        }

        img:nth-of-type(2) {
          position: absolute;
          top: 0;
          opacity: 0;
        }
      }

      &__item_discount {
        position: absolute;
        background: #fdc300;
        color: #ffffff;
        border-radius: 50%;
        font-size: 1.1em;
        font-weight: 400;
        height: 5rem;
        width: 5rem;
        display: flex;
        justify-content: center;
        align-items: center;
        right: 2rem;
        top: 2rem;
        z-index: 7;
      }

      & > div {
        position: relative;
        text-align: center;
        display: flex;
        flex-direction: column;
        transition: 0.4s ease all;
        border-radius: 2rem;
        padding-bottom: 2rem;

        h4 {
          line-height: 1.7rem;
          font-size: 1.44rem;
          max-width: 80%;
          margin: auto;
        }

        img {
          margin: 0 auto;
          object-fit: contain;
          height: 25rem;
          max-height: 25rem;
        }

        &::before {
          content: "";
          position: absolute;
          width: 80%;
          height: 1px;
          background: $gray-fourth;
          top: 0;
          left: 10%;
        }

        &:nth-child(odd) {
          &::after {
            content: "";
            position: absolute;
            width: 1px;
            height: 80%;
            background: $gray-fourth;
            right: 0;
            top: 10%;
          }
        }
      }

      &__name {
        font-size: 1.2rem;
        line-height: 2.8rem;
        min-height: 1.8rem;
      }

      &__price {
        font-weight: 700;
        font-size: 1.6rem;
        margin-top: 1.3rem;
        display: flex;
        justify-content: center;
        align-items: center;

        & > * {
          margin: 0 0.2rem;
        }

        &__trough {
          font-size: 1.1rem;
          font-weight: 400;
          position: relative;

          &::after {
            content: "";
            top: calc(50% - 1px);
            left: 0%;
            background: $black-headers;
            height: 2px;
            width: 100%;
            position: absolute;
          }
        }

        &__discount {
          color: $yellow;
        }
      }

      &__discounts {
        margin-top: 0.4rem;
        min-height: 2.8rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;

        span {
          font-size: 1rem;
          padding: 0.3rem 0.7rem;
          line-height: 1.3rem;
          margin: 0.2rem;
        }
      }
    }
  }

  &__gray_banner {
    height: 73.8rem;
    background: $black-second;
    width: 100%;
    display: block;
  }
}

@media screen and (min-width: $screen-sm-min) {
  .home__eshop {
    .btn-gray {
      padding: 1.5rem 4rem;
      font-size: 15px;
    }

    &__wrapper {
      & > div {
        h4 {
          font-size: 2.2rem;
          line-height: 2.3rem;
          color: $black-headers;
        }
      }

      &__item_discount {
        height: 6.5rem;
        width: 6.5rem;
        font-size: 2rem;
      }

      &__name {
        font-size: 1.63rem;
        margin-top: 0.3rem;
      }

      &__price {
        font-size: 1.9rem;
        color: $black-second;

        &__trough {
          font-size: 1.4rem;
          font-weight: 400;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .home {
    &__header {
      padding-top: 11.1rem;
    }

    &__trailer {
      .center {
        display: block;
      }

      .my-row > div .img_text_wrapper span {
        font-size: 1.8rem;
      }
    }

    &__eshop__wrapper {
      &__item_discount {
        top: 3rem;
        right: 3rem;
      }

      &__discounts {
        span {
          font-size: 1.2rem;
          padding: 0.7rem 1.3rem;
          line-height: 1.3rem;
          margin: 0.2rem;
          font-weight: 400;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) and (max-width: ($screen-xl-min - 1px)) {
  .home__header .my-row > div {
    &:nth-child(odd) {
      justify-content: flex-end;
    }

    &:nth-child(even) {
      justify-content: flex-start;
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .home {
    &__trailer .my-row > div {
      > img {
        padding-left: 1rem;
      }

      .img_text_wrapper {
        span {
          font-size: 2rem;
        }

        img {
          height: 12rem;
        }
      }
    }

    &__eshop {
      &__wrapper {
        padding: 0;

        & > div {
          &:hover {
            -webkit-box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
            -moz-box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
            box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
            z-index: 99;

            .home__eshop__wrapper__img_wrapper {
              img:first-of-type {
                opacity: 0;
              }

              img:last-of-type {
                opacity: 1;
              }
            }
          }

          &:nth-child(n + 7) {
            display: flex;
          }

          &::after {
            content: "";
            position: absolute;
            width: 1px;
            height: 80%;
            background: $gray-fourth;
            right: 0;
            top: 10%;
          }
        }
      }
    }
  }
}

@media screen and (min-width: $screen-xl-min) {
  .home {
    &__eshop {
    }

    &__trailer .my-row > div {
      min-height: 28rem;

      > img {
        padding-left: 1rem;
      }

      &:last-of-type {
        padding-right: 1rem;
      }

      &:first-of-type {
        padding-left: 1rem;
      }

      .img_text_wrapper {
        img {
          height: 11rem;
        }

        span {
          font-size: 1.9rem;
          text-align: center;
          width: 85%;
        }
      }
    }

    &__header {
      padding-top: 18.3rem;
      max-width: 1484px;

      h2 {
        font-size: 3.96rem;
        line-height: 4.3rem;
      }

      .my-row > div {
        padding: 1.6rem;

        .category_wrapper {
          max-width: unset;

          h3 {
            font-size: 2.88rem;
            margin-bottom: 2.2rem;
          }

          ul {
            font-size: 1.44rem;
            margin-bottom: 4.5rem;
          }

          > a {
            font-size: 1.44rem;
            white-space: nowrap;
            margin-bottom: 2.5rem;
          }
        }
      }
    }
  }
}

@media screen and (min-width: $screen-xxl-min) {
  .home {
    &__trailer .my-row > div {
      .img_text_wrapper {
        span {
          font-size: 2.2rem;
        }

        img {
          height: 12rem;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-sm-min) {
  .home {
    &__trailer .my-row > div {
      .img_text_wrapper {
        span {
          font-size: 2.2rem;
        }

        img {
          height: 12rem;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .home {
    &__trailer .my-row > div {
      .img_text_wrapper {
        span {
          font-size: 2rem;
        }

        img {
          height: 11rem;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-fullHd-min) {
  .home {
    &__trailer .my-row > div {
      .img_text_wrapper {
        span {
          font-size: 2.4rem;
        }

        img {
          height: 13rem;
        }
      }
    }
  }
}

.banners {
  margin: 5rem 0 1rem 0;
  padding: 0 1rem;

  & > img {
    display: none;
    margin: auto;
    border-radius: 2.5rem;
  }

  @media screen and (min-width: $screen-xl-min) {
    padding: 0 2rem;
  }
}
</style>
