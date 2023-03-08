<template>
  <div class="info row">
    <div class="info__header row">
      <div class="col-12-xs col-9-lg info__header__main"></div>
      <side-bar-links></side-bar-links>
    </div>
  </div>
</template>

<script>
import CustomMade from "../components/CustomMade.vue";
import SideBarLinks from "../components/SideBarInfoLinks.vue";

export default {
  components: {
    CustomMade,
    SideBarLinks,
  },
};
</script>
