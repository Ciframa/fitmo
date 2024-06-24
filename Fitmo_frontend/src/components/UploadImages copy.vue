<template>
  <div class="cropper">
    <div class="cropper__wrapper">
      <cropper
        ref="cropper"
        class="cropper_ref"
        :src="img"
        @change="change"
        :stencil-props="{
          aspectRatio: 3 / 2,
        }"
      />
      <img :src="image" alt="" />
    </div>
  </div>
</template>

<script>
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";

export default {
  props: {},
  components: {
    Cropper,
  },
  data() {
    return {
      image: "",
      img: "/img/BALANCNI_PODLOZKA_REHABIQ_KOPULE_1_DOWNSIZED.c735e401.jpg",
    };
  },
  methods: {
    change({ coordinates, canvas }) {
      this.image = canvas.toDataURL();
    },
  },
  created() {},
};
</script>
<style lang="scss" scoped>
.cropper {
  max-height: 50rem;
  max-width: 50rem;

  &__wrapper {
    display: flex;
  }
}
</style>
