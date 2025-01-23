<template>
  <div class="paymentDelivery__currentStep">
    <router-link
      to="/objednavka/kosik"
      class="paymentDelivery__currentStep__item"
    >
      <span class="paymentDelivery__currentStep__item__number">1</span>
      <span class="paymentDelivery__currentStep__item__text"
        >Nákupní <br />
        košík</span
      >
    </router-link>
    <img src="../../../public/assets/icons/double_arrow_gray.svg" alt="" />
    <router-link
      to="/objednavka/doprava-a-platba"
      class="paymentDelivery__currentStep__item"
    >
      <span class="paymentDelivery__currentStep__item__number">2</span>
      <span class="paymentDelivery__currentStep__item__text"
        >Doprava<br />
        a platba</span
      >
    </router-link>
    <img src="../../../public/assets/icons/double_arrow_gray.svg" alt="" />
    <router-link
      to="/objednavka/informace"
      class="paymentDelivery__currentStep__item"
    >
      <span class="paymentDelivery__currentStep__item__number">3</span>
      <span class="paymentDelivery__currentStep__item__text"
        >Informace<br />
        o Vás</span
      >
    </router-link>
  </div>
</template>
<script></script>
<style lang="scss">
.customersInfo .paymentDelivery__currentStep__item {
  &:nth-child(5) span:first-child {
    background: $yellow;
  }
}

.cart .paymentDelivery__currentStep__item {
  &:nth-child(1) span:first-child {
    background: $yellow;
  }
}

.paymentDeliveryC .paymentDelivery__currentStep__item {
  &:nth-child(3) span:first-child {
    background: $yellow;
  }
}

.paymentDelivery__currentStep {
  display: none;
  align-items: center;
  max-height: 5.9rem;
  justify-content: space-between;
  margin-bottom: 5rem;

  &__item {
    display: flex;
    align-items: center;

    &__text {
      margin-left: 1.5rem;
      font-size: 2rem;
      font-weight: 600;
      line-height: 2rem;
    }

    &__number {
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 100%;
      width: 5.9rem;
      height: 5.9rem;
      background: $gray-second;
      font-size: 2.9rem;
      font-weight: 500;
      color: $white;
    }

    .active {
      background: $yellow;
    }
  }

  img {
    height: 4rem;
    color: $gray-second;
  }
}

@media screen and (min-width: $screen-lg-min) {
  .paymentDelivery__currentStep {
    padding-right: 2rem;
  }
}
</style>
