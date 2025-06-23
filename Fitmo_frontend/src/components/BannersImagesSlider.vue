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
      <font-awesome-icon
        v-if="slider"
        size="3x"
        :icon="['fas', 'angle-left']"
        @click="slider.prev()"
        :class="{
          arrow__left: true,
          arrow: true,
        }"
      />
      <font-awesome-icon
        v-if="slider"
        size="3x"
        :icon="['fas', 'angle-right']"
        @click="slider.next()"
        :class="{
          arrow__right: true,
          arrow: true,
        }"
      />
    </div>
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
    breakpoints: String,
  },
  name: "BannersImagesSlider",
  setup(props) {
    // Pass props as an argument
    const current = ref(0);
    const [container, slider] = useKeenSlider({
      initial: current.value,
      slideChanged: (s) => {
        current.value = s.track.details.rel;
      },
      loop: true,
      slides: {
        spacing: 10,
        perView: 1, // Use props.perView
      },
      breakpoints: props.breakpoints,
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
.keen-slider {
  position: relative;
}

.arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 1;
  color: $gray-third;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 30px;
  height: 30px;
  padding: 1rem;
  aspect-ratio: 1/1;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  //color: white;
  font-weight: bold;
  font-size: 1.2rem;
  text-shadow: 0 0 5px rgba(0, 0, 0, 0.3);

  &:hover {
    transform: translateY(-50%) scale(1.1);
  }

  &__left {
    left: 1rem;
  }

  &__right {
    right: 1rem;
  }
}

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
