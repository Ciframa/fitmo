<template>
  <div class="paymentDelivery__buttons">
    <router-link
      :to="this.$props.pageBefore"
      class="paymentDelivery__buttons__back btn"
    >
      <img src="../../../public/assets/icons/double_arrow_gray.svg" alt="" />
      <img src="../../../public/assets/icons/double_arrow_white.svg" alt="" />
      Pokračovat v nakupování
    </router-link>
    <router-link
      :to="this.$props.pageAfter"
      class="btn-yellow"
      v-if="this.$props.page != 'informace'"
    >
      Pokračovat v platbě
      <img src="../../../public/assets/icons/double_arrow_white.svg" alt="" />
    </router-link>
  </div>
</template>
<script>
export default {
  props: {
    page: String,
    pageBefore: String,
    pageAfter: String,
  },
  data() {
    return {};
  },
};
</script>
