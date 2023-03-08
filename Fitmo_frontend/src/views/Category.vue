<template>
  <div class="category">
    <div class="category__header">
      <h1>{{ mainCategory.name }}</h1>
      <article>
        <p>
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quisquam,
          necessitatibus! Quod commodi a soluta eos tempora cumque sunt
          cupiditate mollitia magnam hic alias dolore voluptates, qui explicabo
          eum praesentium dolores.Lorem ipsum, dolor sit amet consectetur
          adipisicing elit. Quisquam, necessitatibus! Quod commodi a soluta eos
          tempora cumque sunt cupiditate mollitia magnam hic alias dolore
          voluptates, qui
        </p>
        <p>
          Není problém, rádi Vám s tímhle problémem pomůžeme a Vaší překážku
          vyrobíme.
        </p>
      </article>
      <div class="category__header__list row">
        <router-link
          class="col-6-xs col-3-md"
          v-for="subCategory in subCategories"
          :key="subCategory.id"
          :to="{
            name: 'Category',
            params: { categoryname: subCategory.url_path.split('/') },
          }"
        >
          <div class="category__header__list__item">
            <span>{{ subCategory.name }}</span>
            <img
              src="../../public/assets/products/main/fitness_vybaveni.png"
              alt=""
            />
          </div>
        </router-link>
      </div>
      <!--<CategoryInfo />-->
    </div>
    <div class="category__eshop row">
      <img src="../../public/assets/banners/druhy.png" alt="" />

      <div class="category__eshop__filters__order col-12-xs">
        <ul>
          <li>nejlevnější</li>
          <li>nejdražší</li>
          <li>abecedně</li>
          <li>nejprodávanější</li>
        </ul>
      </div>
      <div class="category__eshop__filters col-12-xs col-4-md col-3-lg">
        <div class="category__eshop__filters__price">
          <span>Cena</span>
          <MultiRangeSlider
            baseClassName="multi-range-slider"
            :min="0"
            :max="100"
            :step="1"
            :ruler="false"
            :label="true"
            :labels="[barMinValue, barMaxValue]"
            :minValue="barMinValue"
            :maxValue="barMaxValue"
            @input="UpdateValues"
          />
        </div>
        <button class="btn-gray" v-on:click="showFilters = !showFilters">
          zobrazit další filtry
        </button>
        <div
          class="category__eshop__filters__brands"
          :class="{
            show: showFilters,
          }"
        >
          <span>Značky</span>
          <div class="category__eshop__filters__brands__list">
            <ul>
              <li v-on:click="provizorniCheck = !provizorniCheck">
                <font-awesome-icon
                  :icon="['fa', 'check']"
                  size="2x"
                  :class="{
                    show: provizorniCheck,
                  }"
                />
                <span>Fitmo</span>
              </li>
              <li v-on:click="provizorniCheck = !provizorniCheck">
                <font-awesome-icon
                  :icon="['fa', 'check']"
                  size="2x"
                  :class="{
                    show: provizorniCheck,
                  }"
                />
                <span>Fitmo</span>
              </li>
              <li v-on:click="provizorniCheck = !provizorniCheck">
                <font-awesome-icon
                  :icon="['fa', 'check']"
                  size="2x"
                  :class="{
                    show: provizorniCheck,
                  }"
                />
                <span>Fitmo</span>
              </li>
              <li v-on:click="provizorniCheck = !provizorniCheck">
                <font-awesome-icon
                  :icon="['fa', 'check']"
                  size="2x"
                  :class="{
                    show: provizorniCheck,
                  }"
                />
                <span>Fitmo</span>
              </li>
              <li v-on:click="provizorniCheck = !provizorniCheck">
                <font-awesome-icon
                  :icon="['fa', 'check']"
                  size="2x"
                  :class="{
                    show: provizorniCheck,
                  }"
                />
                <span>Fitmo</span>
              </li>
            </ul>
          </div>
        </div>
        <div class="category__eshop__filters__info">
          <h4>Poradíme <br />Vám s výběrem</h4>
          <div class="category__eshop__filters__info__wrapper">
            <a href="tel:+420 702 064 265">+420 702 064 265</a>
            <span>po-pá: 8:00 - 17:00</span>
          </div>
          <div class="category__eshop__filters__info__wrapper">
            <a href="mailto:obchod@fitmo.cz">obchod@fitmo.cz</a>
          </div>
          <a href="#">Kontaktujte nás</a>
        </div>
        <img src="../../public/assets/banners/treti.png" alt="" />
        <img src="../../public/assets/banners/treti.png" alt="" />
      </div>
      <div class="home__eshop__wrapper row col-12-xs col-8-md col-9-lg">
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <span class="home__eshop__wrapper__item_discount">-20%</span>
          <img
            src="../../public/assets/products/main/doplnky_stravy.png"
            alt=""
          />
          <h4>OCR VYBAVENÍ</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span class="home__eshop__wrapper__price__trough">450,-</span>
            <span class="home__eshop__wrapper__price__discount">400,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-yellow">Akce</span>
            <span class="btn-blue">Novinka</span>
          </div>
        </div>
        <div class="col-12-xs col-6-sm col-4-lg">
          <span class="home__eshop__wrapper__item_discount">-20%</span>
          <img
            src="../../public/assets/products/main/fitness_vybaveni.png"
            alt=""
          />
          <h4>OCR VYBAVENÍ</h4>
          <span class="home__eshop__wrapper__name">Masážní válec</span>
          <div class="home__eshop__wrapper__price">
            <span class="home__eshop__wrapper__price__trough">450,-</span>
            <span class="home__eshop__wrapper__price__discount">400,-</span>
          </div>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-yellow">Akce</span>
            <span class="btn-blue">Novinka</span>
            <span class="btn-green">Top produkt</span>
          </div>
        </div>
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
        <div class="col-12-xs col-6-sm col-4-lg">
          <img src="../../public/assets/products/main/regenerace.png" alt="" />
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
    </div>
  </div>
</template>

<script>
//import CategoryInfo from "../components/CategoryInfo.vue";
import MultiRangeSlider from "multi-range-slider-vue";
import axios from "../api";

export default {
  components: {
    MultiRangeSlider,
    /*CategoryInfo*/
  },
  data() {
    return {
      url_path: this.$route.params.categoryname,
      mainCategory: "",
      subCategories: [],
      provizorniCheck: false,
      showFilters: false,
      barMinValue: 10,
      barMaxValue: 5000,
    };
  },
  watch: {
    $route(to, from) {
      this.url_path = this.$route.params.categoryname;
      this.getSubCategories();
    },
  },

  methods: {
    async getSubCategories() {
      try {
        const response = await axios.get("/api/category/" + [this.url_path]);
        this.subCategories = response.data;
        this.mainCategory = this.subCategories.pop();
      } catch (error) {
        console.log(error);
      }
    },

    UpdateValues(e) {
      this.barMinValue = e.minValue;
      this.barMaxValue = e.maxValue;
    },
  },
  created() {
    this.getSubCategories();
  },
};
</script>
<style lang="scss">
.category {
  background: $gray-third;

  &__header {
    max-width: 1140px;
    width: 90%;
    text-align: center;
    margin: auto;

    h1 {
      font-size: 2.8rem;
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
        font-size: 1.5rem;
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
      & > a {
        padding: 0.7rem;
        display: flex;
        justify-content: center;

        img {
          height: 9rem;
          padding: 0.3rem 0.3rem 0.3rem 0.8rem;
        }
        span {
          font-weight: 700;
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
    padding: 6rem 5%;
    max-width: 1450px;
    margin: auto;
    display: flex;
    box-shadow: 0 0 0 100vmax white;
    clip-path: inset(0 -100vmax);
    justify-content: space-between;

    & > img {
      -webkit-box-shadow: 0px 0px 41px -4px rgba(0, 0, 0, 0.33);
      -moz-box-shadow: 0px 0px 41px -4px rgba(0, 0, 0, 0.33);
      box-shadow: 0px 0px 41px -4px rgba(0, 0, 0, 0.33);
      border-radius: 2.5rem;
    }

    &__filters {
      padding-right: 1rem;
      img {
        -webkit-box-shadow: 0px 0px 41px -4px rgba(0, 0, 0, 0.33);
        -moz-box-shadow: 0px 0px 41px -4px rgba(0, 0, 0, 0.33);
        box-shadow: 0px 0px 41px -4px rgba(0, 0, 0, 0.33);
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
        background: $gray-third;
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
            background: $gray-second;
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
            -webkit-box-shadow: 0px 0px 5px 1px rgba(0, 0, 0, 0.25);
            -moz-box-shadow: 0px 0px 5px 1px rgba(0, 0, 0, 0.25);
            box-shadow: 0px 0px 5px 1px rgba(0, 0, 0, 0.25);
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
            display: flex;
            align-items: center;

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
    &__header__list {
      /* & > div {
        &:nth-child(odd) {
          padding-left: 0;
        }
        &:nth-child(even) {
          padding-right: 0;
        }
      }*/
    }
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
        justify-content: start;
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
        margin-top: 8rem;
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

        &::after {
          content: "";
          height: 2px;
          width: calc(100% - 6rem);
          background: $gray-second;
          position: absolute;
          bottom: 0;
        }
      }
      &__brands {
        border-radius: 0 0 2rem 2rem;
      }
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .category__eshop__filters__order ul {
    width: 75%;
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
</style>
