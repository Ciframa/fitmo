<template>
  <div class="orderConfirmation">
    <h2>Děkujeme za vaší objednávku!</h2>
    <p>Číslo vaší objednávky:</p>
    <p>
      Váš nákup byl <b>úspěšně DOKONČEN</b>. Na e-mailovou adresu
      <b>{{ order?.user?.email }}</b> Vám bylo odesláno potvrzení objednávky.
    </p>
  </div>
</template>
<script>
import axios from "../../api";
export default {
  data() {
    return {
      order: {},
    };
  },
  methods: {
    async loadOrder() {
      try {
        const response = await axios.get(
          `/api/order/${this.$route.params.orderId}`
        );
        this.order = response.data[0];
        console.log(response.data[0].user.name);
      } catch (error) {
        console.log(error);
      }
    },
  },
  mounted() {
    this.loadOrder();
  },
};
</script>
<style lang="scss">
.orderConfirmation {
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 60vh;
  h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
  }
  p {
    font-size: 1.5rem;
    margin-bottom: 1rem;
  }
}
</style>
