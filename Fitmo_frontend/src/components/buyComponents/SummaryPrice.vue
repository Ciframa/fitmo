<template>
  <div class="paymentDelivery__summary__wrapper">
    <section>
      <span>Souhrn</span>
      <div class="paymentDelivery__summary__wrapper__section">
        <div
          class="paymentDelivery__summary__wrapper__section__my-row"
          v-if="this.cartPriceSummary"
        >
          <span>Celkem za zboží</span>
          <span>{{ this.cartPriceSummary }} Kč</span>
        </div>
        <div
          class="paymentDelivery__summary__wrapper__section__my-row column line"
          v-if="this.deliveryType"
        >
          <div class="my-row">
            <span>Druh dopravy</span>
            <span>{{ this.deliveryType.price }}</span>
          </div>
          <div class="address">{{ this.deliveryType.address?.info }}</div>
        </div>
        <div
          class="paymentDelivery__summary__wrapper__section__my-row column line"
          v-if="this.paymentType"
        >
          <div class="my-row">
            <span>Druh platby</span>
            <span>{{ this.paymentType.price }}</span>
          </div>
          <div class="address">{{ this.paymentType.name }}</div>
        </div>
      </div>
      <div
        class="paymentDelivery__summary__wrapper__section"
        v-if="this.cartSummaryPrice"
      >
        <div class="paymentDelivery__summary__wrapper__section__my-row">
          <span>Celkem za zboží</span>
          <span>{{ this.cartSummaryPrice }} Kč</span>
        </div>
        <!--<div class="paymentDelivery__summary__wrapper__section__my-row">
          <span>Cena bez DPH</span>
          <span>735,54 Kč</span>
        </div>
        -->
      </div>
    </section>
    <section v-if="this.$props.page == 'informace'">
      <!-- <div>
        <input type="checkbox" name="agreement" id="agreement" />
        <label for="agreement">
          <font-awesome-icon :icon="['fa', 'check']" />
          Souhlasím s předáním údajů za účelem hodnocení nákupu pro službu.
        </label>
      </div>
      <div>
        <input type="checkbox" name="newsletter" id="newsletter" />
        <label for="newsletter">
          <font-awesome-icon :icon="['fa', 'check']" />
          Nezasílat dotazník spokojenosti v rámci programu Ověřeno zákazníky
        </label>
      </div>
      -->
      <p>
        Kliknutím na tlačítko "odeslat objednávku" souhlasíte s obchodními
        podmínkami a ochranou osobních údajů.
      </p>
      <button v-on:click="triggerSubmit" class="btn-yellow">
        Odeslat objednávku
        <img src="../../../public/assets/icons/double_arrow_white.svg" alt="" />
      </button>
    </section>
  </div>
</template>
<script>
export default {
  props: {
    page: String,
  },
  data() {
    return {
      priceSummary: 0,
      cartProducts: [],
    };
  },
  methods: {
    async loadItems() {
      this.$store.commit(
        "updatePaymentType",
        JSON.parse(localStorage.getItem("paymentType"))
      );
      this.$store.commit(
        "updateDeliveryType",
        JSON.parse(localStorage.getItem("deliveryType"))
      );
    },
    triggerSubmit() {
      this.$emit("submit-order");
    },
  },
  mounted() {
    this.loadItems();
  },

  computed: {
    paymentType() {
      return this.$store.state.paymentType;
    },
    deliveryType() {
      return this.$store.state.deliveryType;
    },
    cartPriceSummary() {
      let price = 0;
      if (this.$store.state.cart) {
        this.$store.state.cart.forEach((element) => {
          price += element.price * element.count;
        });
      }
      return price;
    },
    cartSummaryPrice() {
      return (
        (this.paymentType?.priceNumber ?? 0) +
        (this.deliveryType?.priceNumber ?? 0) +
        this.cartPriceSummary
      );
    },
  },
};
</script>
<style lang="scss">
@media screen and (min-width: $screen-md-min) {
  .paymentDelivery__summary__wrapper {
    max-width: 34.5rem;
  }

  @media screen and (max-width: $screen-lg-min - 1px) {
    .paymentDelivery__summary__wrapper {
      section:last-child p {
        margin-bottom: auto;
      }
    }
  }
}

.paymentDelivery {
  &__summary {
    &__wrapper {
      background: $white;
      border-radius: 2rem;
      padding: 2.5rem;

      section {
        > span {
          padding-bottom: 1rem;
        }
      }

      > span:first-child {
        font-size: 1.2rem;
      }

      span {
        font-size: 1.4rem;
      }

      &__section {
        position: relative;

        &:nth-child(2) {
          border-bottom: 1px solid $gray;
        }

        &__my-row {
          padding: 0.8rem 0;
          display: flex;
          position: relative;
          justify-content: space-between;

          &.column {
            flex-direction: column;
          }

          .address {
            font-size: 11px;
            line-height: 16px;
          }

          .my-row {
            justify-content: space-between;
            width: 100%;
            display: flex;
          }

          &.line {
            &::after {
              content: "";
              height: 1px;
              width: 100%;
              position: absolute;
              top: 0;
              background-color: $gray-second;
            }
          }

          :last-child {
            font-weight: 400;
          }
        }

        &:nth-child(3) {
          div:first-child {
            span:first-child {
              font-size: 1.7rem;
              font-weight: 400;
            }

            span:last-child {
              font-size: 1.5rem;
              font-weight: 700;
            }
          }
        }
      }

      section:nth-child(2) {
        display: flex;
        flex-direction: column;
        justify-content: space-between;

        > div {
          display: flex;
          align-items: center;

          svg {
            color: $white;
            min-width: 1.8rem;
            min-height: 1.8rem;
            border: 1px solid $gray-second;
            border-radius: 0.5rem;
            margin: auto;
            margin-right: 0.8rem;
          }

          label {
            font-size: 1.1rem;
            line-height: 1.3rem;
            display: flex;
          }

          input[type="checkbox"]:checked + label svg {
            background: $yellow;
            border-color: $yellow;
          }

          &:first-child {
            padding-top: 0.5rem;
          }

          &:nth-child(2) {
            padding-top: 2rem;
            padding-bottom: 2.5rem;
          }
        }

        p {
          font-size: 1.1rem;
          line-height: 1.3rem;
          margin-bottom: 3rem;
        }

        .btn-yellow {
          font-size: 1.4rem;
          width: 100%;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .paymentDelivery {
    &__summary__wrapper {
      width: 100%;
      margin-left: auto;
    }
  }
}

@media screen and (max-width: $screen-md-min - 1px) {
  .paymentDelivery__summary__wrapper section:nth-child(2) {
    margin-top: 1.5rem;
  }
}
</style>
