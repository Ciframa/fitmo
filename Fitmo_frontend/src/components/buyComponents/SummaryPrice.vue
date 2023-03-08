<template>
  <div class="paymentDelivery__summary__wrapper">
    <section>
      <span>Souhrn</span>
      <div class="paymentDelivery__summary__wrapper__section">
        <div class="paymentDelivery__summary__wrapper__section__row">
          <span>Celkem za zboží</span>
          <span>890,-</span>
        </div>
        <div class="paymentDelivery__summary__wrapper__section__row">
          <span>Druh dopravy</span>
          <span>50,-</span>
        </div>
        <div class="paymentDelivery__summary__wrapper__section__row">
          <span>Druh platby</span>
          <span>ZDARMA</span>
        </div>
      </div>
      <div class="paymentDelivery__summary__wrapper__section">
        <div class="paymentDelivery__summary__wrapper__section__row">
          <span>Celkem za zboží</span>
          <span>890,-</span>
        </div>
        <div class="paymentDelivery__summary__wrapper__section__row">
          <span>Cena bez DPH</span>
          <span>735,54,-</span>
        </div>
      </div>
    </section>
    <section v-if="this.$props.page == 'informace'">
      <div>
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
      <p>
        Kliknutím na tlačítko "odeslat objednávku" souhlasíte s obchodními
        podmínkami a ochranou osobních údajů.
      </p>
      <button class="btn-yellow">
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
    return {};
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

      > span:first-child {
        font-size: 1.2rem;
      }

      span {
        font-size: 1.4rem;
      }
      &__section {
        padding: 1rem 0;
        &:nth-child(2) {
          border-bottom: 1px solid $gray-second;
        }
        &__row {
          padding: 0.2rem 0;
          display: flex;
          justify-content: space-between;

          :last-child {
            font-weight: 400;
          }
        }
        &:nth-child(2) {
          div:first-child {
            span:first-child {
              font-size: 1.7rem;
              font-weight: 400;
            }
            span:last-child {
              font-size: 1.4rem;
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
            width: 1.8rem;
            height: 1.8rem;
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
