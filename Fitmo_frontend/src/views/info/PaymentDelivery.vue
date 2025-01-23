<template>
  <div class="payment my-row">
    <div class="col-12-xs col-9-lg info__header__main">
      <h1>Doprava a platba</h1>
      <article>
        <p>
          V případě objednávek s celkovou hmotností <b>do 5 kg</b> doporučujeme
          zvolit <b>Zásilkovnu</b>.<br />
          Vybrat si můžete buď
          <b>doručení na adresu, výdejní místo </b>či <b>Z-box</b>. Pokud si
          chcete nechat doručit balíček až k vašim dveřím, tak sáhněte po
          doručení na adresu. V případě, že si budete chtít balíček vyzvednout
          na některém výdejním místě, zvolte doručit na výdejní místo. Poslední
          možností je zaslání balíčku na vámi zvolený Z-box ve vašem okolí. Pro
          vyzvednutí balíčku stačí mít pouze nainstalovanou aplikaci společnosti
          Zásilkovna, se kterou Z-box otevřete.
        </p>
        <p>
          U objednávek <b>nad 10 kg</b> doporučujeme zvolit <b>PPL</b> či<b>
            Českou poštu</b
          >.
          <br />
          Společnost PPL se vyznačuje zejména svojí spolehlivostí a rychlostí v
          doručování balíčků.
        </p>
        <p>
          Poslední možností jak vám můžeme doručit zásilku je doručení na adresu
          přostřednictvím společnosti Česká Pošta.
        </p>
        <div class="my-row">
          <img src="../../../public/assets/icons/logistics.svg" alt="" />
          <span>Doprava zdarma <br />při nákupu nad 3000 Kč</span>
        </div>
      </article>
      <div class="payment__info_wrapper">
        <h2>Možnosti dopravy</h2>
        <section>
          <div v-for="deliveryType in deliveryTypes" :key="deliveryType.id">
            <div
              class="payment__info_wrapper__item"
              v-on:click="deliveryType.expand = !deliveryType.expand"
            >
              <font-awesome-icon
                :icon="['fa', 'angle-down']"
                size="2x"
                :class="{
                  rotate: deliveryType.expand,
                }"
              />
              <div class="payment__info_wrapper__item__textImg">
                <img :src="imageBasePath + deliveryType.image_path" alt="" />
                <span>{{ deliveryType.name }}</span>
              </div>
              <span class="payment__info_wrapper__item__price">{{
                deliveryType.price
              }}</span>
            </div>
            <div
              class="payment__info_wrapper__item__toShow payment__info_wrapper__item__toShow__shown"
              v-show="deliveryType.expand"
            >
              <p
                v-for="text in deliveryType.texts"
                :key="text.id"
                v-html="text"
              ></p>
            </div>
          </div>
        </section>
      </div>
      <div class="payment__info_wrapper">
        <h2>Způsoby platby</h2>
        <section>
          <div v-for="paymentType in paymentTypes" :key="paymentType.id">
            <div
              class="payment__info_wrapper__item"
              v-on:click="paymentType.expand = !paymentType.expand"
            >
              <font-awesome-icon
                :icon="['fa', 'angle-down']"
                size="2x"
                :class="{
                  rotate: paymentType.expand,
                }"
              />
              <div class="payment__info_wrapper__item__textImg">
                <img :src="imageBasePath + paymentType.image_path" alt="" />
                <span>{{ paymentType.name }}</span>
              </div>
              <span class="payment__info_wrapper__item__price">{{
                paymentType.price
              }}</span>
            </div>
            <div
              class="payment__info_wrapper__item__toShow payment__info_wrapper__item__toShow__shown"
              v-show="paymentType.expand"
            >
              <p
                v-for="text in paymentType.texts"
                :key="text.id"
                v-html="text"
              ></p>
            </div>
          </div>
        </section>
      </div>
    </div>
    <side-bar-links></side-bar-links>
  </div>
</template>

<script>
import SideBarLinks from "../../components/SideBarInfoLinks.vue";
import axios from "../../api";

export default {
  components: { SideBarLinks },

  data() {
    return {
      deliveryTypes: [],
      paymentTypes: [],
      imageBasePath: process.env.VUE_APP_FITMO_BACKEND_URL,
    };
  },
  methods: {
    async getDeliveryTypes() {
      const response = await axios.get("/api/deliveryTypes");

      this.deliveryTypes = response.data.map((item) => {
        const texts = item.description.split("<br>").map((text) => text.trim());
        return {
          ...item,
          expand: false,
          texts: texts,
          price: item.price == 0 ? "ZDARMA" : item.price + " Kč",
        };
      });
    },
    async getPaymentTypes() {
      const response = await axios.get("/api/paymentTypes");

      this.paymentTypes = response.data.map((item) => {
        const texts = item.description.split("<br>").map((text) => text.trim());

        return {
          ...item,
          price: item.price == 0 ? "ZDARMA" : item.price + " Kč",
          expand: false,
          texts: texts,
        };
      });
    },
  },
  mounted() {
    this.getDeliveryTypes();
    this.getPaymentTypes();
  },
};
</script>
<style lang="scss" scoped>
h1 {
  text-align: left;
}
</style>
<style lang="scss">
.payment {
  padding: 5rem 10% 4rem 10%;
  background: $gray-third;

  article {
    margin-top: 2.2rem;
    color: $black-second;
    gap: 0.8rem;
    display: flex;
    flex-direction: column;

    p {
      text-align: justify;
      font-size: 1.5rem;
    }

    .my-row {
      align-items: center;
      margin: 5rem 0;

      img {
        height: 4rem;
      }

      span {
        margin-left: 2rem;
        font-size: 1.6rem;
        line-height: 2rem;
        font-weight: 1000;
      }
    }
  }

  &__info_wrapper {
    width: 100%;

    &:nth-of-type(2) {
      h2 {
        margin-top: 2.5rem;
      }
    }

    h2 {
      text-align: left;
      font-size: 1.5rem;
      margin-bottom: 0.6rem;
    }

    section {
      background: $white;
      border-radius: 2rem;
      padding: 2rem 4rem 2rem 3rem;
    }

    &__item {
      display: flex;
      padding: 1rem 0;
      flex-wrap: wrap;
      cursor: pointer;

      &:hover svg {
        color: $black-headers;
      }

      .rotate {
        transform: rotateX(180deg);
      }

      &__toShow {
        width: 100%;
        display: none;
        padding-left: 5rem;
        margin-bottom: 1.3rem;
        flex-direction: column;
        gap: 0.5rem;

        a {
          display: contents;
          font-weight: 500;
        }

        &__shown {
          display: flex;
          width: calc(100% - 4.4rem);
          text-align: justify;
        }
      }

      > svg {
        color: $gray-second;
      }

      &__textImg {
        margin-left: 2.5rem;
        align-items: center;

        span {
          font-size: 1.2rem;
          display: block;
          line-height: 1.5rem;
          margin-top: 0.7rem;
          font-weight: 600;
        }

        img {
          height: 2.5rem;
          max-width: 15rem;
          max-height: 100%;
          border-radius: 0.5rem;
        }
      }

      &__price {
        margin-left: auto;
        font-weight: 500;
        font-size: 1.4rem;
        white-space: nowrap;
      }
    }

    &:last-child {
      section {
        .payment__info_wrapper__item__textImg {
          span {
            padding-right: 2.5rem;
          }

          img {
            width: 3.5rem;
          }
        }
      }
    }
  }
}

@media screen and (max-width: $screen-sm-min - 1px) {
  .payment__info_wrapper {
    &__item__textImg span {
      line-height: 1.4rem;
    }
  }
}

@media screen and (min-width: $screen-sm-min) {
  .payment__info_wrapper__item__textImg {
    img {
      height: 3.4rem;
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .payment__info_wrapper__item {
    align-items: center;
    flex-wrap: nowrap;

    &__textImg {
      display: flex;

      span {
        margin: 0 1.5rem;
      }
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .payment__info_wrapper__item__textImg span {
    font-size: 1.4rem;
  }
}
</style>
