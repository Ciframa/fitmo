<template>
  <div class="customersInfo paymentDelivery">
    <div class="col-8-lg customersInfo__firstSection">
      <current-step-c></current-step-c>
      <h1>
        Informace<br />
        o Vás
      </h1>
      <div class="customersInfo__form_wrapper">
        <h2>Osobní údaje</h2>
        <form>
          <div
            class="my-row"
            :class="{ 'has-error': validationErrors.customerInfo.nameSurname }"
          >
            <label for="">Jméno a příjmení*</label>
            <input
              v-model="this.deliveryDetails.customerInfo.nameSurname"
              type="text"
              placeholder="Jméno a příjmení"
              @input="validateField('nameSurname', 'customerInfo')"
            />
          </div>
          <div
            class="my-row"
            :class="{ 'has-error': validationErrors.customerInfo.email }"
          >
            <label for="">E-mail*</label>
            <input
              type="email"
              v-model="this.deliveryDetails.customerInfo.email"
              placeholder="E-mail"
              @input="validateField('email', 'customerInfo')"
            />
          </div>
          <div
            class="my-row"
            :class="{
              'has-error':
                validationErrors.customerInfo.phone ||
                validationErrors.customerInfo.prePhone,
            }"
          >
            <label for="">Telefon*</label>
            <div
              class="my-row__select_wrapper"
              :class="{ 'has-error': validationErrors.customerInfo.phone }"
            >
              <div
                class="my-row__select_wrapper__phone"
                :class="{ 'has-error': validationErrors.customerInfo.prePhone }"
              >
                <input
                  type="text"
                  v-model="deliveryDetails.customerInfo.prePhone"
                  @input="validateField('prePhone', 'customerInfo')"
                  class="prephone"
                />
              </div>
              <input
                type="text"
                v-model="this.deliveryDetails.customerInfo.phone"
                @input="validateField('phone', 'customerInfo')"
                placeholder="Telefon"
              />
            </div>
          </div>
        </form>
      </div>
      <div class="customersInfo__form_wrapper">
        <h2>Fakturační adresa</h2>
        <form>
          <div
            class="my-row"
            :class="{ 'has-error': validationErrors.billingInfo.street }"
          >
            <label for="">Ulice a číslo popisné*</label>
            <!-- <vue-google-autocomplete
              :placeholder="'Vyhledat adresu'"
              v-model="this.deliveryDetails.billingInfo.street"
              @placechanged="getAddressData"
              ref="street"
              :options="{ types: ['address'] }"
              @input="validateField('street')"
              :country="['cz', 'sk']"
              id="AutoCompleteAddress"
            /> -->
            <input
              v-model="this.deliveryDetails.billingInfo.street"
              name="zip"
              type="text"
              @input="validateField('street', 'billingInfo')"
              placeholder="Ulice a číslo popisné"
            />
          </div>
          <div
            class="my-row"
            :class="{ 'has-error': validationErrors.billingInfo.city }"
          >
            <label for="city">Město*</label>
            <!--  <vue-google-autocomplete
              ref="city"
              :placeholder="'Vyhledat město'"
              v-model="this.deliveryDetails.billingInfo.city"
              types="(cities)"
              @input="validateField('city')"
              :country="['cz', 'sk']"
              id="AutoCompleteCities"
            /> -->
            <input
              v-model="this.deliveryDetails.billingInfo.city"
              name="zip"
              @input="validateField('city', 'billingInfo')"
              type="text"
              placeholder="Město"
            />
          </div>
          <div
            class="my-row"
            :class="{ 'has-error': validationErrors.billingInfo.zip }"
          >
            <label for="zip">PSČ*</label>
            <input
              v-model="this.deliveryDetails.billingInfo.zip"
              name="zip"
              @input="validateField('zip', 'billingInfo')"
              type="text"
              placeholder="PSČ"
            />
          </div>
          <div
            class="my-row customersInfo__form_wrapper__country__wrapper"
            :class="{ 'has-error': validationErrors.billingInfo.country }"
          >
            <label for="country">Země*</label>
            <select
              v-on:click="
                this.billingInfoShownCountry = !this.billingInfoShownCountry
              "
              name="country"
              v-model="this.deliveryDetails.billingInfo.country"
              class="customersInfo__form_wrapper__country"
            >
              <option value="cz">Česko</option>
              <option value="sk">Slovensko</option>
            </select>
            <font-awesome-icon
              :class="{ rotate: this.billingInfoShownCountry }"
              :icon="['fa', 'angle-down']"
            />
          </div>
        </form>
      </div>
      <div class="billing_information" v-if="deliveryType.id !== 2">
        <input
          type="checkbox"
          name=""
          id="identicalAdresses"
          :checked="this.deliveryDetails.identicalAdresses"
          v-model="this.deliveryDetails.identicalAdresses"
        />
        <label for="identicalAdresses" class="billing_information__check">
          <font-awesome-icon :icon="['fa', 'check']" />
          Fakturační údaje jsou stejné jako dodací</label
        >
      </div>
      <div
        class="customersInfo__form_wrapper"
        v-if="deliveryType.id !== 2 && !this.deliveryDetails.identicalAdresses"
      >
        <h2>Doručovací adresa</h2>
        <form>
          <div
            class="my-row"
            :class="{ 'has-error': validationErrors.deliveryInfo.street }"
          >
            <label for="">Ulice a číslo popisné*</label>
            <!-- <vue-google-autocomplete
              :placeholder="'Vyhledat adresu'"
              v-model="this.deliveryDetails.deliveryInfo.street"
              @placechanged="getAddressData"
              ref="street"
              :options="{ types: ['address'] }"
              @input="validateField('street')"
              :country="['cz', 'sk']"
              id="AutoCompleteAddress"
            /> -->
            <input
              v-model="this.deliveryDetails.deliveryInfo.street"
              name="zip"
              type="text"
              @input="validateField('street', 'deliveryInfo')"
              placeholder="Ulice a číslo popisné"
            />
          </div>
          <div
            class="my-row"
            :class="{ 'has-error': validationErrors.deliveryInfo.city }"
          >
            <label for="city">Město*</label>
            <!--  <vue-google-autocomplete
              ref="city"
              :placeholder="'Vyhledat město'"
              v-model="this.deliveryDetails.deliveryInfo.city"
              types="(cities)"
              @input="validateField('city')"
              :country="['cz', 'sk']"
              id="AutoCompleteCities"
            /> -->
            <input
              v-model="this.deliveryDetails.deliveryInfo.city"
              name="zip"
              @input="validateField('city', 'deliveryInfo')"
              type="text"
              placeholder="Město"
            />
          </div>
          <div
            class="my-row"
            :class="{ 'has-error': validationErrors.deliveryInfo.zip }"
          >
            <label for="zip">PSČ*</label>
            <input
              v-model="this.deliveryDetails.deliveryInfo.zip"
              name="zip"
              @input="validateField('zip', 'deliveryInfo')"
              type="text"
              placeholder="PSČ"
            />
          </div>
          <div
            class="my-row customersInfo__form_wrapper__country__wrapper"
            :class="{ 'has-error': validationErrors.deliveryInfo.country }"
          >
            <label for="country">Země*</label>
            <select
              name="country"
              v-model="this.deliveryDetails.deliveryInfo.country"
              class="customersInfo__form_wrapper__country"
            >
              <option value="cz">Česko</option>
              <option value="sk">Slovensko</option>
            </select>
          </div>
        </form>
      </div>
    </div>
    <div class="col-4-lg customersInfo__secondSection">
      <summary-price-c
        @submit-order="submit"
        page="informace"
      ></summary-price-c>
      <nav-buttons-c
        page="informace"
        pageBefore="/objednavka/doprava-a-platba"
        pageAfter="/"
      ></nav-buttons-c>
    </div>
  </div>
</template>
<script>
import currentStepC from "../../components/buyComponents/CurrentStep.vue";
import summaryPriceC from "../../components/buyComponents/SummaryPrice.vue";
import navButtonsC from "../../components/buyComponents/NavButtons.vue";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import axios from "../../api";

export default {
  components: {
    currentStepC,
    summaryPriceC,
    navButtonsC,
    VueGoogleAutocomplete,
  },
  data() {
    return {
      value: "",
      billingInfoShownCountry: false,
      loggedUser: null,
      selectedAddress: "",
      validationErrors: {
        customerInfo: {
          nameSurname: null,
          email: null,
          prePhone: null,
          phone: null,
        },
        billingInfo: {
          street: null,
          city: null,
          zip: null,
          country: null,
        },
        deliveryInfo: {
          street: null,
          city: null,
          zip: null,
          country: null,
        },
      },
      deliveryDetails: {
        deliveryType: null,
        paymentType: null,
        identicalAdresses: true,
        customerInfo: {
          nameSurname: "",
          email: "",
          prePhone: "+420",
          phone: "",
        },
        billingInfo: {
          street: "",
          city: "",
          zip: "",
          country: "cz",
          type: "billing",
        },
        deliveryInfo: {
          street: "",
          city: "",
          zip: "",
          country: "cz",
          type: "home",
        },
      },
    };
  },
  methods: {
    getAddressData(place, info) {
      const components = this.getAddressComponents(info);
      this.deliveryDetails.billingInfo.street = components.route;
      this.deliveryDetails.billingInfo.zip = components.postal_code;
      this.deliveryDetails.billingInfo.city = components.postal_town;
      this.deliveryDetails.billingInfo.country = components.country;

      this.$refs.street.update(components.route);
      this.$refs.city.update(components.postal_town);
      this.validateForm();
    },
    getAddressComponents(place) {
      let components = {};
      place.address_components.forEach((component) => {
        components[component.types[0]] = component.long_name;
      });
      return components;
    },
    validateField(fieldName, object) {
      // Implement your validation logic here
      // For simplicity, let's assume a simple presence check for now
      const value = this.deliveryDetails[object][fieldName];
      if (fieldName == "phone") {
        this.validationErrors[object][fieldName] =
          value.replaceAll(" ", "").length === 9
            ? null
            : "Telefon musí obsahovat 9 číslic";
      } else if (fieldName == "email") {
        this.validationErrors[object][fieldName] = value
          .toLowerCase()
          .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          )
          ? null
          : "Nevyhovující formát emailu";
      } else {
        this.validationErrors[object][fieldName] = value
          ? null
          : "Toto pole je povinné";
      }
    },

    validateForm() {
      // Validate only if submitted

      // Validate all fields
      Object.keys(this.validationErrors.customerInfo).forEach((fieldName) => {
        this.validateField(fieldName, "customerInfo");
      });
      // Check if there are any validation errors
      const customerInfoHasErrors = Object.values(
        this.validationErrors.customerInfo
      ).some((error) => !!error);

      Object.keys(this.validationErrors.billingInfo).forEach((fieldName) => {
        this.validateField(fieldName, "billingInfo");
      });
      // Check if there are any validation errors
      const billingHasErrors = Object.values(
        this.validationErrors.billingInfo
      ).some((error) => !!error);

      Object.keys(this.validationErrors.deliveryInfo).forEach((fieldName) => {
        this.validateField(fieldName, "deliveryInfo");
      });

      const deliveryHasErrors = Object.values(
        this.validationErrors.deliveryInfo
      ).some((error) => !!error);

      return (
        !billingHasErrors &&
        (!deliveryHasErrors || this.deliveryDetails.identicalAdresses) &&
        !customerInfoHasErrors
      );
    },
    async submit() {
      if (this.validateForm()) {
        if (this.deliveryDetails.identicalAdresses) {
          this.deliveryDetails.deliveryInfo = {
            ...this.deliveryDetails.billingInfo,
            type: "home",
          };
        }
        try {
          const response = await axios.post("/api/order/store", {
            customerInfo: this.deliveryDetails,
            cart: this.cart,
            userId: this.loggedUser?.id ?? null,
          });
          if (response.status === 200) {
            console.log(response);
            this.$store.commit("updateDeliveryType", []);
            this.$store.commit("updatePaymentType", []);
            this.$store.commit("updateCart", []);
            localStorage.removeItem("paymentType");
            localStorage.removeItem("deliveryType");
            localStorage.removeItem("cart");
            this.$router.push({
              path: "/objednavka/potvrzeni/" + response.data.order_id,
            });
          }
        } catch (error) {
          console.log(error);
        }
      } else {
        console.log("Form has validation errors. Please fix them.");
      }
    },
    async loadItems() {
      this.loggedUser = JSON.parse(sessionStorage.getItem("user"));
      this.$store.commit(
        "updatePaymentType",
        JSON.parse(localStorage.getItem("paymentType"))
      );
      this.deliveryDetails.paymentType = JSON.parse(
        localStorage.getItem("paymentType")
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

  computed: {
    cart() {
      return this.$store.state.cart;
    },
    deliveryType() {
      if (this.$store.state.deliveryType.address) {
        this.deliveryDetails.deliveryInfo =
          this.$store.state.deliveryType.address;
        this.deliveryDetails.identicalAdresses = false;
      }
      this.deliveryDetails.deliveryType = this.$store.state.deliveryType;
      return this.$store.state.deliveryType;
    },
  },

  mounted() {
    this.loadItems();
  },
};
</script>
<style lang="scss">
.customersInfo {
  .has-error {
    & > * {
      border-color: red; // Add your preferred error styling
    }

    label {
      color: red;
    }
  }

  &__form_wrapper {
    margin-top: 3rem;

    h2 {
      text-align: left;
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    form {
      background: $white;
      border-radius: 2rem;
      padding: 3rem 4rem;

      ::-webkit-input-placeholder,
      select::placeholder {
        color: $gray-second;
      }

      .my-row {
        align-items: center;
        max-width: 48rem;
        margin: 1rem 0;

        &__select_wrapper {
          margin-left: auto;
          display: flex;
          width: 28rem;

          &__phone {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 6rem;
            position: relative;

            & + input {
              width: 21rem;
            }
          }

          .prephone {
            width: 6rem;
          }
        }
      }

      input {
        width: 28rem;
        padding: 1rem 1.4rem;
        margin-left: auto;
      }
    }

    &__country {
      width: 28rem;
      padding: 1rem;
      border-radius: 1rem;
      border-color: $gray-second;
      color: $gray;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      padding-right: 2em; /* space for arrow */

      &.rotate svg {
        transform: rotateX(180deg);
      }

      &__wrapper {
        position: relative;
        display: inline-block;

        svg {
          position: absolute;
          pointer-events: none;
          right: 10px;
          top: 50%;
          transform: translateY(-50%);
        }
      }

      div {
        margin: 0;
      }

      &:focus-visible {
        outline: 0;
      }
    }
  }
}

@media screen and (max-width: $screen-md-min - 1px) {
  .customersInfo__form_wrapper form .my-row {
    flex-direction: column;
    align-items: flex-start;

    input,
    &__select_wrapper {
      margin-left: unset;
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .customersInfo__form_wrapper__country {
    margin-left: auto;
  }

  @media screen and (max-width: $screen-lg-min - 1px) {
    .customersInfo {
      .paymentDelivery__summary__wrapper {
        display: flex;
        width: 100%;
        max-width: 100%;
        background: $gray-third;
        padding: 0;
        gap: 2rem;

        section {
          background: $white;
          width: 50%;
          padding: 2.5rem;
          border-radius: 2rem;
        }
      }
    }
  }
}

@media screen and (max-width: $screen-lg-min - 1px) {
  .customersInfo {
    .paymentDelivery__summary__wrapper {
      margin-top: 3rem;
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .customersInfo {
    display: flex;
    flex-wrap: wrap;
    padding-bottom: 5rem;

    .paymentDelivery {
      &__currentStep {
        padding-right: 0;
      }

      &__buttons {
        justify-content: center;
      }
    }

    &__firstSection {
      padding-right: 2rem;
    }
  }
}
</style>
