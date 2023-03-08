<template>
  <div class="home">
    <div class="home__header">
      <h2>PŘIPRAVTE SE NA OCR ZÁVODY KOMPLEXNĚ. <br />STAČÍ SI JEN VYBRAT!</h2>

      <div class="row">
        <div
          v-for="category in categories"
          :key="category.id"
          class="col-12-xs col-6-md col-3-xl"
        >
          <div class="category_wrapper">
            <img
              src="../../public/assets/products/main/ocr_vybaveni.png"
              alt=""
            />
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
        <div class="home__header__showroom">
          <img src="../../public/assets/banners/druhy.png" alt="" />
        </div>
      </div>
    </div>
    <div class="home__trailer">
      <div class="row">
        <div class="col-12-xs col-6-sm col-3-xl">
          <img src="../../public/assets/toChange/prvni.jpg" alt="" />
          <div class="img_text_wrapper">
            <img src="../../public/assets/icons/quality.svg" alt="" />
            <span>KVALITA ZBOŽÍ</span>
          </div>
        </div>
        <div class="col-12-xs col-6-sm col-3-xl">
          <img src="../../public/assets/toChange/druhy.jpg" alt="" />
          <div class="img_text_wrapper">
            <img src="../../public/assets/icons/equipment.svg" alt="" />
            <span>VYBAVENÍ DO GYMŮ</span>
          </div>
        </div>
        <div class="col-12-xs col-6-sm col-3-xl">
          <img src="../../public/assets/toChange/treti.jpg" alt="" />
          <div class="img_text_wrapper">
            <img src="../../public/assets/icons/one_place.svg" alt="" />
            <span>VŠE NA JEDNOM MÍSTĚ</span>
          </div>
        </div>
        <div class="col-12-xs col-6-sm col-3-xl">
          <img src="../../public/assets/toChange/ctvrty.jpg" alt="" />
          <div class="img_text_wrapper">
            <img src="../../public/assets/icons/ocr.svg" alt="" />
            <span>SPECIALISTA NA OCR ZÁVODY!</span>
          </div>
        </div>
      </div>
    </div>
    <div class="home__eshop row">
      <h3 class="col-12-xs">E-SHOP</h3>
      <div class="home__eshop__wrapper row col-12-xs">
        <div class="col-6-xs col-4-lg col-3-xl">
          <span class="home__eshop__wrapper__item_discount">-20%</span>
          <div class="home__eshop__wrapper__img_wrapper">
            <img
              src="../../public/assets/products/main/regenerace.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
          <h4>POINT FOAM ROLLER GRID 1.0 CAMU GREY</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span class="home__eshop__wrapper__price__trough">450,-</span>
            <span class="home__eshop__wrapper__price__discount">400,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-yellow">Akce</span>
            <span class="btn-green">Top produkt</span>
          </div>
        </div>
        <div class="col-6-xs col-4-lg col-3-xl">
          <span class="home__eshop__wrapper__item_discount">-20%</span>
          <div class="home__eshop__wrapper__img_wrapper">
            <img src="../../public/assets/products/provizorni/1.jpg" alt="" />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
          <h4>TRIGGER POINT FOAM ROLLER GRID 1.0 MINT</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span class="home__eshop__wrapper__price__trough">450,-</span>
            <span class="home__eshop__wrapper__price__discount">400,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-green">Top produkt</span>
          </div>
        </div>
        <div class="col-6-xs col-4-lg col-3-xl">
          <span class="home__eshop__wrapper__item_discount">-20%</span>
          <div class="home__eshop__wrapper__img_wrapper">
            <img src="../../public/assets/products/provizorni/2.jpg" alt="" />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
          <h4>RUSH ROLLER</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span>450,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts"></div>
        </div>
        <div class="col-6-xs col-4-lg col-3-xl">
          <div class="home__eshop__wrapper__img_wrapper">
            <img src="../../public/assets/products/provizorni/3.jpg" alt="" />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
          <h4>NANO LTE FOOT ROLLER</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span>450,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-green">Top produkt</span>
          </div>
        </div>

        <div class="col-6-xs col-4-lg col-3-xl">
          <div class="home__eshop__wrapper__img_wrapper">
            <img
              src="../../public/assets/products/main/regenerace.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
          <h4>OCR VYBAVENÍ</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span>450,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-yellow">Akce</span>
            <span class="btn-green">Top produkt</span>
            <span class="btn-blue">Novinka</span>
          </div>
        </div>
        <div class="col-6-xs col-4-lg col-3-xl">
          <div class="home__eshop__wrapper__img_wrapper">
            <img
              src="../../public/assets/products/main/regenerace.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
          <h4>OCR VYBAVENÍ</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span>450,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-yellow">Akce</span>
            <span class="btn-green">Top produkt</span>
          </div>
        </div>
        <div class="col-6-xs col-4-lg col-3-xl">
          <div class="home__eshop__wrapper__img_wrapper">
            <img
              src="../../public/assets/products/main/regenerace.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
          <h4>OCR VYBAVENÍ</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span>450,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-yellow">Akce</span>
            <span class="btn-green">Top produkt</span>
          </div>
        </div>
        <div class="col-6-xs col-4-lg col-3-xl">
          <div class="home__eshop__wrapper__img_wrapper">
            <img
              src="../../public/assets/products/main/regenerace.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
          <h4>OCR VYBAVENÍ</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span>450,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-yellow">Akce</span>
            <span class="btn-green">Top produkt</span>
          </div>
        </div>
      </div>
      <button class="btn-gray">Zobrazit další produkty</button>
      <img src="../../public/assets/banners/druhy.png" alt="" />
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

export default {
  components: {
    CustomMade,
    Slider,
  },

  data() {
    return {
      categories: [],
      ratings: [],
    };
  },

  methods: {
    getCategories() {
      axios
        .get("/api/categories")
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
  },

  created() {
    this.getCategories();
    this.getRatings();
  },
};
</script>
<style lang="scss">
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

    .row {
      display: flex;
      margin-top: 5rem;

      > div {
        display: flex;
        justify-content: center;
        padding: 1rem;

        .category_wrapper {
          background: $white;
          margin-top: 5rem;
          display: flex;
          border-radius: 2rem;
          flex-direction: column;
          padding: 3rem 0 0 3rem;
          position: relative;
          max-width: 30rem;

          img {
            max-height: 22rem;
            position: absolute;
            top: 0;
            left: calc(50% - 11rem);
            transform: translateY(-41%);
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
  &__trailer {
    color: $white;
    font-weight: 400;
    font-size: 1.8rem;
    position: relative;

    .row > div {
      position: relative;
      > img {
        padding: 0.5rem;
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
          height: 12rem;
        }
        span {
          font-weight: 700;
          font-size: 1.6rem;
        }
      }
    }
  }
  &__eshop {
    max-width: 1520px;
    margin: auto;
    justify-content: space-between;
    padding: 0 5%;

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
        height: 100%;
        display: flex;
        justify-content: center;

        img {
          transition: 0.3s ease all;
          width: 100%;
          height: 100%;
          margin: 0 auto;
        }
        img:last-child {
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
          max-width: 90%;
          margin: 0 auto;
          object-fit: contain;
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
        &:nth-child(n + 7) {
          display: none;
        }
      }

      &__name {
        font-size: 1.2rem;
        line-height: 2rem;
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
            left: 0;
            background: $gray;
            height: 1px;
            width: 73%;
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

@media screen and(min-width: $screen-sm-min) {
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
        }
      }

      &__item_discount {
        height: 6.5rem;
        width: 6.5rem;
        font-size: 2rem;
      }
      &__name {
        font-size: 1.7rem;
        margin-top: 0.3rem;
      }
      &__price {
        font-size: 1.9rem;
        &__trough {
          font-size: 1.4rem;
          font-weight: 400;
        }
      }
    }
  }
}
@media screen and(min-width: $screen-md-min) {
  .home {
    &__header {
      padding-top: 11.1rem;
    }
    &__trailer {
      .center {
        display: block;
      }
      .row > div .img_text_wrapper span {
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
  .home__header .row > div {
    &:nth-child(odd) {
      justify-content: flex-end;
    }
    &:nth-child(even) {
      justify-content: flex-start;
    }
  }
}

@media screen and(min-width: $screen-lg-min) {
  .home {
    &__trailer .row > div {
      > img {
        padding-left: 0.5rem;
      }
      .img_text_wrapper {
        span {
          font-size: 2rem;
        }
        img {
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
            .home__eshop__wrapper__img_wrapper {
              img:first-child {
                opacity: 0;
              }
              img:last-child {
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
@media screen and(min-width: $screen-xl-min) {
  .home {
    &__eshop {
    }
    &__trailer .row > div {
      min-height: 28rem;
      .img_text_wrapper {
        span {
          font-size: 2.2rem;
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
      .row > div {
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
</style>
