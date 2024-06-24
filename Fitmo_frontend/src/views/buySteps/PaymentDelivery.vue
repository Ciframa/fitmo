<template>
  <div class="paymentDeliveryC paymentDelivery">
    <h1>
      Doprava<br />
      a platba
    </h1>

    <section class="col-12-xs col-8-lg">
      <current-step-c class="col-12-xs"></current-step-c>
      <div class="payment__info_wrapper">
        <h2>Zvolte způsob dopravy</h2>
        <section>
          <div
            class="payment__info_wrapper__item"
            v-for="deliveryType in deliveryTypes"
            v-on:click="
              if (deliveryType.id === 2) {
                openZasilkovnaPickupPoint(deliveryType);
              } else {
                changeDeliveryType(deliveryType);
              }
            "
            :key="deliveryType.id"
          >
            <input
              type="radio"
              name="deliveryType"
              :id="deliveryType.name"
              :checked="deliveryType.id === storedDeliveryType?.id"
            />
            <label :for="deliveryType.name">
              <font-awesome-icon :icon="['fa', 'check']" />
            </label>
            <div class="payment__info_wrapper__item__textImg">
              <img :src="imageBasePath + deliveryType.image_path" alt="" />
              <span>{{ deliveryType.name }}</span>
            </div>
            <span class="payment__info_wrapper__item__price">{{
              deliveryType.price
            }}</span>
          </div>
        </section>
      </div>
      <div class="payment__info_wrapper">
        <h2>Zvolte způsob platby</h2>
        <section>
          <div
            v-for="paymentType in paymentTypes"
            :key="paymentType.id"
            v-on:click="changePaymentType(paymentType)"
            class="payment__info_wrapper__item"
          >
            <input
              type="radio"
              name="paymentType"
              :id="paymentType.name"
              :checked="paymentType.id === storedPaymentType?.id"
            />
            <label :for="paymentType.name">
              <font-awesome-icon :icon="['fa', 'check']" />
            </label>
            <div class="payment__info_wrapper__item__textImg">
              <img :src="imageBasePath + paymentType.image_path" alt="" />
              <span>{{ paymentType.name }}</span>
            </div>
            <span class="payment__info_wrapper__item__price">{{
              paymentType.price
            }}</span>
          </div>
        </section>
      </div>
    </section>
    <section class="col-12-xs col-4-lg">
      <summary-price-c page="doprava-a-platba"></summary-price-c>
      <nav-buttons-c
        page="doprava-a-platba"
        pageBefore="/objednavka/kosik"
        pageAfter="/objednavka/informace"
      ></nav-buttons-c>
    </section>
  </div>
</template>
<script>
import currentStepC from "../../components/buyComponents/CurrentStep.vue";
import summaryPriceC from "../../components/buyComponents/SummaryPrice.vue";
import navButtonsC from "../../components/buyComponents/NavButtons.vue";
import axios from "../../api";

export default {
  components: {
    currentStepC,
    summaryPriceC,
    navButtonsC,
  },
  data() {
    return {
      deliveryTypes: [],
      paymentTypes: [],
      priceSummary: 0,
      imageBasePath: "http://localhost:8000",
      // imageBasePath: "https://be.fitmo.cz",
      packetaApiKey: "APIe6ee80786c2d92b2",
      packetaOptions: {
        valueFormat:
          '"Packeta",id,carrierId,carrierPickupPointId,name,city,street',
      },
    };
  },

  methods: {
    async getDeliveryTypes() {
      const response = await axios.get("/api/deliveryTypes");

      this.deliveryTypes = response.data.map((item) => {
        return {
          ...item,
          price: item.price == 0 ? "ZDARMA" : item.price + " Kč",
          priceNumber: item.price,
        };
      });
    },

    async getPaymentTypes() {
      const response = await axios.get("/api/paymentTypes");

      this.paymentTypes = response.data.map((item) => {
        return {
          ...item,
          price: item.price == 0 ? "ZDARMA" : item.price + " Kč",
          priceNumber: item.price,
        };
      });
    },

    changeDeliveryType(deliveryType) {
      sessionStorage.removeItem("deliveryType");
      sessionStorage.setItem(
        "deliveryType",
        JSON.stringify({
          id: deliveryType.id,
          price: deliveryType.price,
          priceNumber: deliveryType.priceNumber,
          address: deliveryType.address ?? "",
        })
      );
      const newDeliveryType = {
        id: deliveryType.id,
        price: deliveryType.price,
        priceNumber: deliveryType.priceNumber,
        address: deliveryType.address ?? "",
      };
      this.$store.commit("updateDeliveryType", newDeliveryType);
    },
    showSelectedPickupPoint(point) {
      // Add here an action on pickup point selection
      if (point) {
        this.changeDeliveryType(
          this.deliveryTypes.find((item) => {
            if (item.id === 2) {
              item.address = {
                city: point.city,
                street: point.street,
                zip: point.zip,
                country: point.country,
                type: "zasilkovna",
                info: point.place,
              };
            }
            return item.id === 2;
          })
        );
      }
    },
    openZasilkovnaPickupPoint() {
      Packeta.Widget.pick(
        this.packetaApiKey,
        this.showSelectedPickupPoint,
        this.packetaOptions
      );
    },

    changePaymentType(paymentType) {
      sessionStorage.removeItem("paymentType");
      sessionStorage.setItem(
        "paymentType",
        JSON.stringify({
          id: paymentType.id,
          price: paymentType.price,
          priceNumber: paymentType.priceNumber,
          name: paymentType.name,
        })
      );

      const newPaymentType = {
        id: paymentType.id,
        price: paymentType.price,
        priceNumber: paymentType.priceNumber,
        name: paymentType.name,
      };
      this.$store.commit("updatePaymentType", newPaymentType);
    },
  },
  computed: {
    storedPaymentType() {
      return this.$store.state.paymentType;
    },
    storedDeliveryType() {
      return this.$store.state.deliveryType;
    },
  },
  created() {
    this.getDeliveryTypes();
    this.getPaymentTypes();
  },
};
</script>
<style lang="scss">
.paymentDelivery {
  margin: auto;
  background: $gray-third;
  padding-left: 6%;
  padding-right: 6%;
  justify-content: space-between;

  h1 {
    text-align: left;
    font-size: 2.3rem;
    line-height: 3rem;
    margin-bottom: 2.2rem;
  }
  &__buttons {
    display: flex;
    justify-content: space-between;
    margin: 3rem auto;
    width: 100%;
    align-items: center;
  }
  .btn-yellow {
    padding: 1.3rem 3rem;
    font-size: 1.6rem;
    font-weight: 500;
    border-radius: 3rem;
    display: flex;
    align-items: center;
    margin: 0;
    white-space: nowrap;
    justify-content: space-between;

    img {
      height: 2.7rem;
    }
  }

  &__back {
    margin: 0.6rem 0 3.6rem 0;
  }
  &__back,
  &__buttons__back {
    display: flex;
    align-items: center;
    padding: 0.2rem 2rem;
    font-size: 1.6rem;
    font-weight: 500;
    color: $gray-second;
    border-radius: 3rem;
    height: 100%;
    white-space: nowrap;

    img {
      width: 2.7rem;
      transform: rotate(180deg);
    }
  }
}
@media screen and (min-width: $screen-md-min) {
  .paymentDelivery {
    &__currentStep {
      display: flex;
    }
    h1 {
      display: none;
    }
  }

  @media screen and (max-width: $screen-lg-min - 1px) {
    //todo šedé tlačítko
  }
}
.paymentDeliveryC {
  display: flex;
  width: 100%;
  flex-wrap: wrap;
  padding-bottom: 10rem;
  position: relative;
  section {
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
  }
  label {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.7rem;
    border: 1px solid $gray-second;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: auto 0;

    svg {
      color: $white;
      width: 2rem;
      height: 2rem;
    }
  }
  .payment__info_wrapper {
    &__item {
      width: 100%;

      &:hover {
        input[type="radio"]:checked + label svg {
          color: $white;
        }
      }
    }
  }
  input[type="radio"]:checked + label {
    background: $yellow;
    border: $yellow;
  }
  & > section {
    &:last-child {
      display: flex;
      flex-direction: column-reverse;
    }
  }
}
@media screen and (min-width: $screen-lg-min) {
  .paymentDeliveryC {
    .payment__info_wrapper {
      padding-right: 2rem;
    }
    .paymentDelivery {
      &__buttons {
        flex-direction: column;
        gap: 2rem;
        max-width: 34.5rem;
        align-items: flex-end;
        margin: 3rem 0 3rem auto;
        a {
          width: 100%;

          &.btn-yellow {
            justify-content: center;
          }
        }
      }
    }
    & > section {
      &:last-child {
        flex-direction: column;
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .paymentDeliveryC {
    .payment__info_wrapper {
    }
    & > section {
      &:last-child {
        margin-bottom: 3rem;
      }
    }
  }

  @media screen and (max-width: $screen-lg-min - 1px) {
    .paymentDeliveryC {
      flex-direction: column-reverse;
      padding-top: 15rem;
      padding-bottom: 13rem;

      .paymentDelivery {
        &__buttons {
          position: absolute;
          bottom: 2rem;
        }
        &__currentStep {
          position: absolute;
          top: 5rem;
          width: 88%;
        }
        &__buttons {
          width: 88%;
          .btn-yellow {
            padding: 1rem 3rem;
            font-size: 1.6rem;
          }
          &__back {
            background: $gray-second;
            color: $white;
            padding: 1rem 3rem;
            font-size: 1.6rem;

            img:first-child {
              display: none;
            }
          }
        }
      }
    }
  }
}
@media screen and (max-width: $screen-md-min - 1px) {
  .paymentDeliveryC {
    .paymentDelivery {
      &__buttons {
        &__back {
          img:last-child {
            display: none;
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
  }
}
</style>
