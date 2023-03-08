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
        <form action="">
          <div class="row">
            <label for="">Jméno a příjmení*</label>
            <input type="text" placeholder="Jméno a příjmení" />
          </div>
          <div class="row">
            <label for="">E-mail*</label>
            <input type="email" placeholder="E-mail" />
          </div>
          <div class="row">
            <label for="">Telefon*</label>
            <div class="row__select_wrapper">
              <div class="row__select_wrapper__phone">
                <img
                  src="../../../public/assets/icons/path_down_gray.svg"
                  alt="Path down"
                />
                <select name="" id="">
                  <option value="">+420</option>
                  <option value="">+241</option>
                  <option value="">+215</option>
                </select>
              </div>
              <input type="text" placeholder="Telefon" />
            </div>
          </div>
        </form>
      </div>
      <div class="customersInfo__form_wrapper">
        <h2>Fakturační a doručovací adresa</h2>
        <form action="">
          <div class="row">
            <label for="">Ulice a číslo domu</label>
            <input type="text" placeholder="Ulice a číslo domu" />
          </div>
          <div class="row">
            <label for="">Město*</label>
            <input type="text" placeholder="Město" />
          </div>
          <div class="row">
            <label for="">PSČ*</label>
            <input type="text" placeholder="PSČ" />
          </div>
          <div class="row">
            <label for="">Země*</label>
            <select name="" id="" class="customersInfo__form_wrapper__country">
              <option value="">Česká republika</option>
              <option value="">Slovensko</option>
            </select>
          </div>
        </form>
      </div>
    </div>
    <div class="col-4-lg customersInfo__secondSection">
      <summary-price-c page="informace"></summary-price-c>
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

export default {
  components: {
    currentStepC,
    summaryPriceC,
    navButtonsC,
  },
};
</script>
<style lang="scss">
.customersInfo {
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
      select {
        color: $gray-second;
      }

      .row {
        align-items: center;
        max-width: 48rem;
        margin: 1rem 0;
        &__select_wrapper {
          margin-left: auto;
          display: flex;

          &__phone {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 6rem;
            position: relative;

            img {
              height: 0.7rem;
              position: absolute;
              left: 0.5rem;
            }
            select {
              width: 100%;
              text-align: right;
            }
            & + input {
              width: 22rem;
            }
          }
          select {
            -webkit-appearance: none;
            -moz-appearance: none;
            text-indent: 1px;
            text-overflow: "";
            padding: 1rem 0.4rem 1rem 0;
            border: none;
            margin-right: 0.3rem;
            border: 1px solid transparent;
            border-radius: 0.5rem 0.5rem 0 0;

            option {
              color: blue;
            }

            &:focus-visible {
              outline: 0;
              border-color: $gray-second;
            }
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

      div {
        margin: 0;
      }
      &:focus-visible {
        outline: 0;
      }
    }
  }
}

@media screen and(max-width: $screen-md-min - 1px ) {
  .customersInfo__form_wrapper form .row {
    flex-direction: column;
    align-items: flex-start;

    input,
    &__select_wrapper {
      margin-left: unset;
    }
  }
}
@media screen and(min-width: $screen-md-min) {
  .customersInfo__form_wrapper__country {
    margin-left: auto;
  }

  @media screen and(max-width: $screen-lg-min - 1px ) {
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
@media screen and(max-width: $screen-lg-min - 1px ) {
  .customersInfo {
    .paymentDelivery__summary__wrapper {
      margin-top: 3rem;
    }
  }
}
@media screen and(min-width: $screen-lg-min) {
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
