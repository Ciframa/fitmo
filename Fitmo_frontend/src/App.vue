<script setup>
import Header from "./views/Header.vue";
import Footer from "./views/Footer.vue";
import { Vue3Snackbar } from "vue3-snackbar";
import AddCartModal from "@/components/AddCartModal.vue";
</script>

<template>
  <div>
    <AddCartModal></AddCartModal>
    <Header></Header>
    <RouterView class="content" />
    <Footer></Footer>
    <vue3-snackbar top right :duration="4000"></vue3-snackbar>
  </div>
</template>

<style lang="scss">
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body,
html {
  height: 100vh;
  max-width: 100vw;
  scroll-behavior: smooth;
  margin: auto;
  position: relative;
}

.home.content {
  padding: 0;
}

.content {
  padding-top: 5rem;
}

.content {
}
</style>
