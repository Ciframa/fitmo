<template>
  <div
    :class="this.class"
    class="images-wrapper banners__slide__wrapper my-row"
  >
    <div ref="container" class="keen-slider">
      <img
        :class="this.class"
        class="keen-slider__slide banners__slide"
        v-for="image in images"
        :key="image.id"
        :src="imageBasePath + image.path"
        alt=""
      />
    </div>
    <!--    <div @click="$refs.slider.prev()">ahoj</div>-->
    <div class="dots">
      <button
        v-for="(_slide, idx) in dotHelper"
        @click="slider.moveToIdx(idx)"
        :class="{ dot: true, active: current === idx }"
        :key="idx"
      ></button>
    </div>
  </div>
</template>

<script>
import "keen-slider/keen-slider.min.css";
import { computed, ref } from "vue";
import { useKeenSlider } from "keen-slider/vue.es";

export default {
  props: {
    images: {
      type: Array,
      default: () => [],
    },
    imageBasePath: String,
    class: String,
  },
  name: "BannersImagesSlider",
  setup() {
    const current = ref(1);
    const [container, slider] = useKeenSlider({
      initial: current.value,
      slideChanged: (s) => {
        current.value = s.track.details.rel;
      },
      loop: true,
      mode: "free-snap",
      slides: {
        spacing: 10,
        perView: 1,
      },
    });

    const dotHelper = computed(() =>
      slider.value
        ? [...Array(slider.value.track?.details?.slides.length).keys()]
        : []
    );
    return { container, current, dotHelper, slider };
  },
  watch: {
    images: () => {
      slider.update();
    },
  },
};
</script>

<style scoped lang="scss">
.banners__slide {
  padding: 0;

  &__wrapper {
    .dots {
      margin-top: 1rem;
      margin-bottom: 3rem;

      .dot {
        margin: 0.5rem;
        background: #c5c5c5;
      }

      .dot.active {
        background: $yellow;
      }
    }
  }
}
</style>
