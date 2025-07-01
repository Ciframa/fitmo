<template>
  <div class="cart paymentDelivery">
    <current-step-c
      v-if="this.cartProducts?.length > 0"
      class="col-12-xs col-8-lg"
    ></current-step-c>
    <h1>
      Nákupní <br />
      košík
    </h1>
    <div class="cart__wrapper">
      <template v-if="this.cartProducts?.length > 0">
        <div class="cart__wrapper__headings">
          <span>Produkt</span>
          <span>Dostupnost</span>
          <span>Množství</span>
          <span>Cena/ks</span>
          <span>Cena celkem</span>
        </div>
        <div
          class="col-12-xs cart__wrapper__item_wrapper"
          v-for="product in this.cartProducts"
          :key="product"
        >
          <div class="cart__wrapper__item_wrapper__header">
            <img
              :src="
                product.color_name
                  ? this.imagesBasePath +
                    product.name +
                    '-' +
                    product.color_name +
                    '/' +
                    product.image_urls[0]
                  : product.variant
                  ? this.imagesBasePath +
                    product.name +
                    '-' +
                    product.variant +
                    '/' +
                    product.image_urls[0]
                  : this.imagesBasePath +
                    product.name +
                    '/' +
                    product.image_urls[0]
              "
              alt=""
            />
            <div>
              <span class="cart__wrapper__item_wrapper__header__title"
                >{{ product.name }}
              </span>
              <span>{{ product?.variant ?? product?.color_name }}</span>
            </div>
          </div>
          <div class="cart__wrapper__item_wrapper__to_hide">
            <span>Dostupnost</span>
            <span>Množství</span>
          </div>
          <section>
            <span class="cart__wrapper__item_wrapper__availibility">
              <div
                class="cart__wrapper__item_wrapper__availibility__text_wrapper"
                :class="this.getAvailibility(product)?.['type']"
              >
                <font-awesome-icon
                  :icon="[
                    'fa',
                    this.getAvailibility(product)?.['type'] !== 'danger'
                      ? 'check'
                      : 'times',
                  ]"
                  size="2x"
                />
                <p>
                  {{ this.getAvailibility(product)?.["text"] }}
                  <template
                    v-if="this.getAvailibility(product)?.['type'] === 'success'"
                  >
                    ({{ this.getAvailibility(product)?.["count"] }})
                  </template>
                </p>
              </div>
            </span>
          </section>
          <section class="cart__wrapper__item_wrapper__count">
            <div class="number_input">
              <div class="number_input__minus">-</div>
              <div class="number_input__count">{{ product.count }}</div>
              <div class="number_input__plus">+</div>
            </div>
          </section>
          <div class="cart__wrapper__item_wrapper__to_hide">
            <span>Cena/ks</span>
          </div>
          <section>
            <span class="cart__wrapper__item_wrapper__priceItem"
              >{{ getLowestPrice(product) }}/ks</span
            >
          </section>
          <section>
            <span class="cart__wrapper__item_wrapper__priceFinal"
              ><span>{{ getLowestPrice(product) * product.count }} Kč</span>
              <font-awesome-icon
                :icon="['fa', 'times']"
                :style="{ fontSize: '2.8rem' }"
                v-on:click="removeItem(product.id)"
              />
            </span>
          </section>
        </div>
      </template>
      <template v-if="!this.products">
        <span>Košík je zatím prázdný</span>
      </template>
    </div>
    <nav-buttons-c
      v-if="this.products?.length > 0"
      page="kosik"
      pageBefore="/"
      pageAfter="/objednavka/doprava-a-platba"
    ></nav-buttons-c>
    <summary-price-c
      v-if="this.products?.length > 0"
      class="col-12-xs col-4-lg"
      page="kosik"
    ></summary-price-c>
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
      imagesBasePath: `https://be.fitmo.cz/products/`,
      cartProducts: [],
    };
  },

  methods: {
    removeItem(itemToRemove) {
      // Get the current items from sessionStorage
      const storedItems = this.cartProducts;

      // Filter out the items that match the criteria (id and count)
      const filteredItems = storedItems.filter(
        (item) => item.id !== itemToRemove
      );

      // Save the filtered array back to sessionStorage
      localStorage.setItem("cart", JSON.stringify(filteredItems));

      this.$store.commit("updateCart", filteredItems);
    },

    getLowestPrice(product) {
      if (product.discount !== 0) {
        return product.discount;
      } else {
        return product.price;
      }
    },

    async getProducts() {
      if (this.products.length > 0) {
        const ids = [];
        this.products.forEach((item) => {
          ids.push(item.id);
        });
        try {
          const response = await axios.get("/api/productsByIds/" + ids);
          if (response.data.length) {
            const secondArrayMap = new Map(
              this.products.map((item) => [item.id, item.count])
            );

            const mappedArray = response.data.map((item) => ({
              ...item,
              count: secondArrayMap.get(item.id) || 0,
            }));
            this.cartProducts = mappedArray;
            this.$store.commit("updateCart", this.cartProducts);
          }
        } catch (error) {}
      } else {
        this.cartProducts = [];
      }
    },
    getAvailibility(product) {
      if (product.manageStock) {
        if (product.stockInformation === "allow") {
          return { text: "Skladem", type: "success", count: ">5" };
        }
        if (product.stock > 5) {
          return { text: "Skladem", type: "success", count: ">5" };
        } else {
          if (product.stock > 0) {
            return { text: "Skladem", type: "success", count: product.stock };
          }
          if (product.stock === 0) {
            if (product.stockInformation === "notAllowed") {
              return { text: "Není skladem", type: "danger", count: 0 };
            }
            if (product.stockInformation === "allowButInform") {
              return { text: "Na objednávku", type: "warning", count: 0 };
            }
          }
        }
      } else {
        if (product.stockInformation === "onStock") {
          return { text: "Skladem", type: "success", count: ">5" };
        }
        if (product.stockInformation === "onOrder") {
          return { text: "Na objednávku", type: "warning", count: ">5" };
        }
        if (product.stockInformation === "notOnStock") {
          return { text: "Není skladem", type: "danger", count: 0 };
        }
      }
    },
    onUpdate(product) {
      if (
        product.count > product.stock &&
        (product.stockInformation !== "allowButInform" ||
          product.stockInformation !== "allow" ||
          product.stockInformation !== "onOrder")
      ) {
        this.cartProducts.forEach((item) => {
          if (item.id === product.id) {
            item.count = product.stock;
          }
        });
        this.$snackbar.add({
          type: "error",
          text:
            "Není možné objednat více kusů, než je skladem. Skladem:" +
            product.stock,
        });
      } else if (product.count < 1) {
        this.cartProducts.forEach((item) => {
          if (item.id === product.id) {
            item.count = 1;
          }
        });
      }
      this.cartProducts.map((item) => {
        if (item.id === product.id) {
          item.count = product.count;
          return;
        }
      });

      this.$store.commit("updateCart", this.cartProducts);
      localStorage.setItem("cart", JSON.stringify(this.cartProducts));
    },
  },
  computed: {
    products() {
      console.log(this.$store.state.cart);
      return this.$store.state.cart;
    },
  },
  mounted() {
    this.getProducts();
  },
};
</script>
<style lang="scss">
.number_input {
  display: flex;
  border: 2px solid $gray-second;
  border-radius: 2rem;
  padding: 0.4rem 1.2rem;
  justify-content: center;
  font-size: 1.6rem;
  align-items: center;
  gap: 1.6rem;
  color: $black-headers;

  &__minus,
  &__plus {
    background: $gray-third;
    border-radius: 50%;
    aspect-ratio: 1/1;
    height: 2.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    transition: background 0.3s ease;
    cursor: pointer;

    &:hover {
      background: $yellow;
    }
  }

  &__count {
    font-weight: 600;
  }
}

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

      &__availibility {
        font-size: 1.5rem;
        display: flex;
        flex-direction: column;

        &__text_wrapper {
          display: flex;
          align-items: center;

          &.danger {
            color: red;
          }

          &.warning {
            color: orange;
          }

          &.success {
            color: $green;
          }
        }
      }

      &__count {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        color: red;
      }

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
        align-items: center;

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
          padding: 1rem 3rem;
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
          width: 13.2rem;
          display: flex;
          justify-content: space-between;
          align-items: center;
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
          width: 9.6rem;
        }

        span:nth-child(4) {
          width: 4.9rem;
        }

        span:nth-child(5) {
          width: 15.2rem;
        }
      }

      &__item_wrapper {
        &__header {
          img {
            height: 10rem;
            width: 10rem;
            object-fit: contain;
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
