<template>
  <div class="changeReturn row">
    <div class="col-12-xs col-9-lg info__header__main">
      <h1>Výměna</h1>
    </div>
    <side-bar-links></side-bar-links>
  </div>
</template>

<script>
import SideBarLinks from "../../components/SideBarInfoLinks.vue";

export default {
  components: { SideBarLinks },
};
</script>
<style lang="scss">
.changeReturn {
  padding: 5rem 10% 4rem 10%;
  background: $gray-third;
}
</style>
