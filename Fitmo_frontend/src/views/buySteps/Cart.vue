<template>
  <div class="cart paymentDelivery">
    <current-step-c class="col-12-xs col-8-lg"></current-step-c>
    <h1>
      Nákupní <br />
      košík
    </h1>
    <div class="cart__wrapper">
      <div class="cart__wrapper__headings">
        <span>Produkt</span>
        <span>Dostupnost</span>
        <span>Množství</span>
        <span>Cena/ks</span>
        <span>Cena celkem</span>
      </div>
      <div class="col-12-xs cart__wrapper__item_wrapper">
        <div class="cart__wrapper__item_wrapper__header">
          <img
            src="../../../public/assets/products/main/fitness_vybaveni.png"
            alt=""
          />
          <div>
            <span class="cart__wrapper__item_wrapper__header__title"
              >NÁZEV PRODUKTU JÁ JSEM PRODUKT KOBLÍŽEK
            </span>
            <span>Hmotnost: 2 kg</span>
          </div>
        </div>
        <div class="cart__wrapper__item_wrapper__to_hide">
          <span>Dostupnost</span>
          <span>Množství</span>
        </div>
        <section>
          <span class="cart__wrapper__item_wrapper__availibility">
            <font-awesome-icon :icon="['fa', 'check']" size="2x" />
            Skladem
          </span>
        </section>
        <section>
          <vue-number-input
            :model-value="1"
            :min="1"
            :inputtable="false"
            inline
            controls
          ></vue-number-input>
        </section>
        <div class="cart__wrapper__item_wrapper__to_hide">
          <span>Cena/ks</span>
        </div>
        <section>
          <span class="cart__wrapper__item_wrapper__priceItem">890 Kč/ks</span>
        </section>
        <section>
          <span class="cart__wrapper__item_wrapper__priceFinal"
            >890 Kč
            <font-awesome-icon :icon="['fa', 'times']" size="2x" />
          </span>
        </section>
      </div>
      <div class="col-12-xs cart__wrapper__item_wrapper">
        <div class="cart__wrapper__item_wrapper__header">
          <img
            src="../../../public/assets/products/main/fitness_vybaveni.png"
            alt=""
          />
          <div>
            <span class="cart__wrapper__item_wrapper__header__title"
              >TRIGGER POINT STK CONTOUR</span
            >
            <span>Hmotnost: 2 kg</span>
          </div>
        </div>
        <div class="cart__wrapper__item_wrapper__to_hide">
          <span>Dostupnost</span>
          <span>Množství</span>
        </div>
        <section>
          <span class="cart__wrapper__item_wrapper__availibility">
            <font-awesome-icon :icon="['fa', 'check']" size="2x" />
            Skladem
          </span>
        </section>
        <section>
          <vue-number-input
            :model-value="1"
            :min="1"
            :inputtable="false"
            inline
            controls
          ></vue-number-input>
        </section>
        <div class="cart__wrapper__item_wrapper__to_hide">
          <span>Cena/ks</span>
        </div>
        <section>
          <span class="cart__wrapper__item_wrapper__priceItem">890 Kč/ks</span>
        </section>
        <section>
          <span class="cart__wrapper__item_wrapper__priceFinal"
            >890 Kč
            <font-awesome-icon :icon="['fa', 'times']" size="2x" />
          </span>
        </section>
      </div>
      <div class="col-12-xs cart__wrapper__item_wrapper">
        <div class="cart__wrapper__item_wrapper__header">
          <img
            src="../../../public/assets/products/main/fitness_vybaveni.png"
            alt=""
          />
          <div>
            <span class="cart__wrapper__item_wrapper__header__title"
              >NÁZEV PRODUKTU</span
            >
            <span>Hmotnost: 2 kg</span>
          </div>
        </div>
        <div class="cart__wrapper__item_wrapper__to_hide">
          <span>Dostupnost</span>
          <span>Množství</span>
        </div>
        <section>
          <span class="cart__wrapper__item_wrapper__availibility">
            <font-awesome-icon :icon="['fa', 'check']" size="2x" />
            Skladem
          </span>
        </section>
        <section>
          <vue-number-input
            :model-value="1"
            :min="1"
            :inputtable="false"
            inline
            controls
          ></vue-number-input>
        </section>
        <div class="cart__wrapper__item_wrapper__to_hide">
          <span>Cena/ks</span>
        </div>
        <section>
          <span class="cart__wrapper__item_wrapper__priceItem">890 Kč/ks</span>
        </section>
        <section>
          <span class="cart__wrapper__item_wrapper__priceFinal"
            >890 Kč
            <font-awesome-icon :icon="['fa', 'times']" size="2x" />
          </span>
        </section>
      </div>
      <div class="col-12-xs cart__wrapper__item_wrapper">
        <div class="cart__wrapper__item_wrapper__header">
          <img
            src="../../../public/assets/products/main/fitness_vybaveni.png"
            alt=""
          />
          <div>
            <span class="cart__wrapper__item_wrapper__header__title"
              >NÁZEV PRODUKTU</span
            >
            <span>Hmotnost: 2 kg</span>
          </div>
        </div>
        <div class="cart__wrapper__item_wrapper__to_hide">
          <span>Dostupnost</span>
          <span>Množství</span>
        </div>
        <section>
          <span class="cart__wrapper__item_wrapper__availibility">
            <font-awesome-icon :icon="['fa', 'check']" size="2x" />
            Skladem
          </span>
        </section>
        <section>
          <vue-number-input
            :model-value="1"
            :min="1"
            :inputtable="false"
            inline
            controls
          ></vue-number-input>
        </section>
        <div class="cart__wrapper__item_wrapper__to_hide">
          <span>Cena/ks</span>
        </div>
        <section>
          <span class="cart__wrapper__item_wrapper__priceItem">890 Kč/ks</span>
        </section>
        <section>
          <span class="cart__wrapper__item_wrapper__priceFinal"
            >890 Kč
            <font-awesome-icon :icon="['fa', 'times']" size="2x" />
          </span>
        </section>
      </div>
    </div>

    <nav-buttons-c
      page="kosik"
      pageBefore="/"
      pageAfter="/objednavka/doprava-a-platba"
    ></nav-buttons-c>
    <summary-price-c class="col-12-xs col-4-lg" page="kosik"></summary-price-c>
  </div>
</template>
<script>
import currentStepC from "../../components/buyComponents/CurrentStep.vue";
import summaryPriceC from "../../components/buyComponents/SummaryPrice.vue";
import navButtonsC from "../../components/buyComponents/NavButtons.vue";

export default {
  components: {
    currentStepC,
    summaryPriceC,
    navButtonsC,
  },

  data() {
    return {};
  },
};
</script>
<style lang="scss">
.cart {
  position: relative;
  padding-bottom: 10rem;

  .vue-number-input {
    &__button--plus,
    &__button--minus {
      &:before {
        //TODO Předělat barvy
      }
      &:hover {
      }
    }
    &__input {
      border-radius: 1rem !important;
    }
  }

  &__wrapper {
    &__headings {
      display: none;
      font-size: 1.2rem;
      color: $gray-second;
      justify-content: space-between;

      span {
        text-align: center;
      }
      span:first-child {
        width: 20.9rem;
        text-align: start;
      }
      span:nth-child(2) {
        width: 7.7rem;
      }
      span:nth-child(3) {
        width: 10rem;
      }
      span:nth-child(4) {
        width: 5.2rem;
      }
      span:nth-child(5) {
        width: 11.2rem;
        text-align: start;
      }
    }
    &__item_wrapper {
      background: $white;
      border-radius: 1rem;
      padding: 3rem;
      padding-bottom: 4rem;
      display: flex;
      flex-wrap: wrap;
      margin: 1rem auto;

      &__priceItem {
        color: $black-second;
      }

      &__priceFinal {
        color: $black-second;
      }
      &__header {
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;

        img {
          height: 10rem;
          margin-right: 1rem;
        }
        &__title {
          font-size: 1.7rem;
          font-weight: 700;
          margin-bottom: 0.2rem;
          word-break: break-word;
          color: $black-headers;
        }
        span:last-child {
          font-size: 1.4rem;
        }

        & > div {
          display: flex;
          flex-direction: column;
          margin-top: 5%;
        }
      }
      &__availibility {
        display: flex;
        color: $green;
        font-weight: 500;

        svg {
          margin-right: 0.4rem;
          width: 1.7rem;
        }
      }
      &__to_hide {
        color: $gray-second;
        font-size: 1.1rem;
        width: 100%;
        display: flex;
        justify-content: space-between;

        :nth-child(2) {
          margin-right: 0.3rem;
        }

        &:nth-child(2n) {
          margin-top: 1.8rem;
        }
      }
      section {
        width: 50%;
        display: flex;

        &:nth-child(4),
        &:nth-child(7) {
          justify-content: flex-end;
        }
        &:last-child span {
          font-weight: 500;
          display: flex;
          align-items: center;

          svg {
            margin-left: 3rem;
            margin-right: 0.3rem;
          }
        }
      }
    }
  }
}
@media screen and (min-width: $screen-md-min) {
  .cart {
    display: flex;
    flex-wrap: wrap;
    &__wrapper {
      width: 100%;
    }

    .paymentDelivery {
      &__currentStep {
        order: 1;
      }
      &__summary__wrapper {
        order: 2;
      }
      &__buttons {
        order: 4;

        .btn-yellow {
          padding: 0.5rem 3rem;
        }
        &__back {
          background: $gray-second;
          color: $white;
          padding: 0.5rem 3rem;
          font-size: 1.6rem;

          img:first-child {
            display: none;
          }
        }
      }
    }

    &__wrapper {
      order: 3;
      margin-top: 4rem;
      &__headings {
        display: flex;
      }
      &__item_wrapper {
        align-items: center;
        justify-content: space-between;
        padding: 2rem;

        &__header {
          img {
            height: 5.9rem;
          }
          & > div {
            margin: 0;
            justify-content: center;
          }
          &__title {
            font-size: 1.1rem;
            width: 12rem;
            line-height: 1.7rem;
          }
          span:last-child {
            font-size: 0.9rem;
          }
        }
        section {
          width: unset;
        }
        &__to_hide {
          display: none;
        }

        &__availibility {
          font-size: 1.2rem;
        }
        &__priceItem {
          font-size: 1rem;
        }
        &__priceFinal {
          font-weight: 400;
          font-size: 1.2rem;
        }
      }
    }
  }
  @media screen and (max-width: $screen-lg-min - 1px) {
    .cart .paymentDelivery__summary__wrapper {
      margin-top: 4rem;
    }
  }
}
@media screen and (min-width: $screen-lg-min) {
  .cart {
    .paymentDelivery {
      &__currentStep {
        order: 1;
        margin: auto;
      }
      &__summary__wrapper {
        order: 2;
      }
      &__buttons {
        order: 4;
      }
    }
    &__wrapper {
      order: 3;
      &__headings {
        span:first-child {
          width: 32rem;
        }
        span:nth-child(2) {
          width: 9.1rem;
        }
        span:nth-child(3) {
          width: 10rem;
        }
        span:nth-child(4) {
          width: 7.1rem;
        }
        span:nth-child(5) {
          width: 14.9rem;
        }
      }
      &__item_wrapper {
        &__header {
          img {
            height: 10rem;
          }

          &__title {
            font-size: 1.8rem;
            width: 19rem;
            line-height: 2.2rem;
          }
          span:last-child {
            font-size: 1.5rem;
          }
        }
        &__availibility {
          font-size: 1.5rem;
        }

        &__priceItem {
          font-size: 1.4rem;
        }
        &__priceFinal {
          font-weight: 700;
          font-size: 2rem;
        }
      }
    }
  }
}
@media screen and (max-width: $screen-md-min - 1px) {
  .cart {
    .paymentDelivery {
      &__buttons {
        &__back {
          img:last-child {
            display: none;
          }
        }
      }
    }
    .btn-yellow {
      position: absolute;
      bottom: 2rem;
      left: 50%;
      transform: translateX(-50%);
    }
  }
}
</style>
