<template>
  <div class="product">
    <div class="product__header">
      <div class="product__header__item row">
        <div class="product__header__item__header">
          <h1>NÁZEV PRODUKTU</h1>
          <h2>Upřesnění</h2>
          <div class="product__header__rating">
            <font-awesome-icon :icon="['fa', 'star']" />
            <font-awesome-icon :icon="['fa', 'star']" />
            <font-awesome-icon :icon="['fa', 'star']" />
            <font-awesome-icon :icon="['fa', 'star']" />
            <font-awesome-icon :icon="['fa', 'star']" />
          </div>
        </div>
        <div class="product__header__item__img_wrapper">
          <span class="home__eshop__wrapper__item_discount">-20%</span>
          <img
            src="../../public/assets/products/main/doplnky_stravy.png"
            alt=""
          />
          <div class="product__header__item__img_wrapper__more">
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
            <img
              src="../../public/assets/products/main/doplnky_stravy.png"
              alt=""
            />
          </div>
        </div>
        <div class="product__header__item__footer">
          <p class="product__header__item__footer__info">
            Stručný popis ve dvou až 3 větách. Stručný popis ve dvou až 3
            větách. Stručný popis ve dvou až 3 větách.
          </p>
          <div class="home__eshop__wrapper__discounts">
            <span class="btn-yellow">Akce</span>
            <span class="btn-blue">Novinka</span>
          </div>
          <div class="home__eshop__wrapper__price">
            <span class="home__eshop__wrapper__price__trough">450,-</span>
            <span class="home__eshop__wrapper__price__discount">400,-</span>
          </div>
          <div class="product__header__item__footer__buy">
            <vue-number-input
              :model-value="1"
              :min="1"
              :inputtable="false"
              inline
              controls
            ></vue-number-input>
            <button class="btn-yellow">Přidat do košíku</button>
          </div>
        </div>
      </div>
    </div>
    <div class="product__description">
      <h2>Popis produktu</h2>
      <p>
        Dřevěné válečky pro posílení úchopu, předloktí. Skvělá pomůcka pro
        horolezce, závodníky překážkových závodů i borce, co cvičí street
        workout. Silný úchop je klíčem k úspěchu, ať už děláte streetworkout,
        powerlifting či horolezectví. Je to naprosto základní předpoklad pro
        podání maximálního výkonu. Ani sebesilnější tělo nevyužijete bez
        pořádného úchopu, ať už jde o mrtvé tahy, přítahy nebo ručkování!
      </p>
      <h2>Popis produktu</h2>
      <div class="product__description__info">
        <div>
          <p>Kategorie</p>
          <p>Dřevěné koule</p>
        </div>
        <div>
          <p>Průměr</p>
          <p>40 mm</p>
        </div>
        <div>
          <p>Materiál</p>
          <p>dřevo</p>
        </div>
        <div>
          <p>Povrchová úprava</p>
          <p>Lak</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {
    /*CategoryInfo*/
  },
  data() {
    return {
      value: 1,
    };
  },
  methods: {},
};
</script>
<style lang="scss">
.product {
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
    padding: 10rem 0;
    max-width: 815px;

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
      margin-top: 1rem;
    }

    &__item {
      display: grid !important;
      grid-auto-flow: row;
      margin: auto;
      column-gap: 3rem;

      &__img_wrapper {
        margin: auto;
        position: relative;

        &__more {
          display: flex;
          gap: 1.3rem;
          img {
            height: 7.5rem;
            -webkit-box-shadow: 0px 0px 23px -5px rgba(0, 0, 0, 0.19);
            -moz-box-shadow: 0px 0px 23px -5px rgba(0, 0, 0, 0.19);
            box-shadow: 0px 0px 23px -5px rgba(0, 0, 0, 0.19);
            border-radius: 2.5rem;
            padding: 0.6rem;
          }
        }
      }
      &__footer {
        margin-right: auto;

        &__info {
          max-width: 30rem;
          margin: 1rem 0;
          line-height: 2rem;
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
            font-size: 2.7rem;
            gap: 0.8rem;

            &__trough {
              font-size: 2.1rem;

              &::after {
                width: 8.5rem;
                left: -0.5rem;
                height: 1px;
              }
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
    padding: 5rem 15%;
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
      max-width: 41rem;
      padding-bottom: 7rem;

      &__footer {
        display: flex;
        margin-top: 2rem;
        flex-wrap: wrap;

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

          button.btn-yellow {
            position: absolute;
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
        grid-row: 1 / span 3;
        overflow: hidden;
        padding-left: 1.3rem;
        padding-bottom: 1.3rem;
      }
      &__footer {
        grid-column: 2 / span 1;
        grid-row: 2 / span 1;
      }
      &__header {
        margin-top: 6rem;
      }
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .product__header {
    max-width: 964px;
    &__item {
      grid-template-columns: 446px 1fr;

      &__footer {
        width: 100%;
      }
    }
  }
}
</style>