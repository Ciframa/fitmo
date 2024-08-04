<template>
  <div class="administration" v-if="this.loggedUser()?.rights === 2">
    <div class="sideBar">
      <ul>
        <li v-on:click="showedMain = 1">Přidat produkt</li>
        <li v-on:click="showedMain = 2">Přehled produktů</li>
        <li v-on:click="showedMain = 3">Přehled kategorií</li>
        <li v-on:click="showedMain = 4">Přehled objednávek</li>
      </ul>
    </div>
    <div class="mainWrapper">
      <AddProduct v-if="showedMain === 1" />
      <AllProducts v-if="showedMain === 2" />
      <AllCategories v-if="showedMain === 3" />
      <AllOrders v-if="showedMain === 4" />
    </div>
  </div>
</template>

<script>
import AddProduct from "../components/admin/AddProduct";
import AllProducts from "../components/admin/AllProducts";
import AllCategories from "../components/admin/AllCategories";
import AllOrders from "../components/admin/AllOrders.vue";
export default {
  components: { AddProduct, AllProducts, AllCategories, AllOrders },
  data() {
    return {
      showedMain: 0,
    };
  },

  methods: {
    loggedUser() {
      return JSON.parse(sessionStorage.getItem("user"));
    },
  },
  mounted() {},
};
</script>
<style lang="scss">
.admin {
}
.administration {
  display: flex;
  position: relative;

  .sideBar {
    background: $gray-third;
    padding: 3rem 0;
    white-space: nowrap;
    margin-top: unset;
    > ul > li {
      padding: 1rem 3rem;
      border-bottom: 1px solid black;
    }
  }
  .mainWrapper {
    width: 100%;
    padding: 0 2rem;
  }
}
</style>
