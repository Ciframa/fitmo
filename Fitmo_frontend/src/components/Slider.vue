<template>
  <div>
    <div class="ratings-wrapper">
      <div v-if="slider" class="dots">
        <button
          v-for="(_slide, idx) in dotHelper"
          @click="slider.moveToIdx(idx)"
          :class="{ dot: true, active: current === idx }"
          :key="idx"
        ></button>
      </div>
      <div ref="container" class="keen-slider">
        <div
          class="keen-slider__slide"
          v-for="rating in ratings"
          :key="rating.id"
        >
          <div class="keen-slider__slide__header my-row">
            <img
              :src="`${imageBasePath}/${rating.img_path}`"
              alt="default.png"
            />
            <div class="keen-slider__slide__header__info">
              <span class="keen-slider__slide__header__info__name">{{
                rating.name
              }}</span>
              <span class="keen-slider__slide__header__info__source">{{
                rating.source
              }}</span>
              <div class="keen-slider__slide__header__info__stars">
                <font-awesome-icon
                  v-for="i in 5"
                  :key="i"
                  :icon="['fa', 'star']"
                  :class="{ checked: i <= rating.rating }"
                />
              </div>
            </div>
          </div>
          <p class="keen-slider__slide__article">{{ rating.article }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, ref } from "vue";

import { useKeenSlider } from "keen-slider/vue.es";
import "keen-slider/keen-slider.min.css";

export default {
  props: {
    ratings: {},
  },
  setup() {
    const current = ref(1);
    const [container, slider] = useKeenSlider(
      {
        initial: current.value,
        slideChanged: (s) => {
          current.value = s.track.details.rel;
        },
        loop: true,
        mode: "free-snap",
        renderMode: "performance",
        slides: {
          spacing: 15,
          perView: "auto",
        },
      },
      [
        (slider) => {
          let timeout;
          let mouseOver = false;

          function clearNextTimeout() {
            clearTimeout(timeout);
          }

          function nextTimeout() {
            clearTimeout(timeout);
            if (mouseOver) return;
            timeout = setTimeout(() => {
              slider.next();
            }, 1500);
          }

          slider.on("created", () => {
            slider.container.addEventListener("mouseover", () => {
              mouseOver = true;
              clearNextTimeout();
            });
            slider.container.addEventListener("mouseout", () => {
              mouseOver = false;
              nextTimeout();
            });
            nextTimeout();
          });
          slider.on("dragStarted", clearNextTimeout);
          slider.on("animationEnded", nextTimeout);
          slider.on("updated", nextTimeout);
        },
      ]
    );

    const dotHelper = computed(() =>
      slider.value
        ? [...Array(slider.value.track.details?.slides.length).keys()]
        : []
    );
    return { container, current, dotHelper, slider };
  },
  data() {
    return {
      imageBasePath: `https://be.fitmo.cz/ratings`,
    };
  },
  watch: {
    ratings: () => {
      slider.update();
    },
  },
};
</script>

<style lang="scss">
.ratings-wrapper {
  position: relative;
  margin: auto;
  padding: 7rem 1rem 15rem 1rem;
}

.keen-slider {
  align-items: flex-start;

  &__slide {
    background: $white;
    border-radius: 1.5rem;
    display: flex;
    flex-wrap: wrap;
    padding: 2rem;
    padding-bottom: 3rem;
    color: $black-headers;
    //min-height: 200px !important;
    min-width: 30rem;
    align-items: flex-start;

    &__header {
      display: flex;
      margin-bottom: 1rem;

      img {
        height: 5.5rem;
        width: 5.5rem;
        border-radius: 50%;
        margin-top: 0.3rem;
      }

      &__info {
        display: flex;
        flex-direction: column;
        margin-left: 1.2rem;

        &__name {
          font-weight: 700;
          white-space: nowrap;
          font-size: 1.5rem;
        }

        &__source {
          line-height: 0.6rem;
          font-size: 1.2rem;
        }

        &__stars {
          margin-top: 0.7rem;

          .checked {
            color: orange;
          }
        }
      }
    }

    &__article {
      font-size: 1.3rem;
      font-weight: 500;
      line-height: 1.7rem;
    }
  }
}

.dots {
  display: none;
  @media screen and (min-width: $screen-md-min) {
    display: flex;
    margin-bottom: 2rem;
    justify-content: center;
  }
}

.dot {
  border: none;
  width: 15px;
  height: 15px;
  background: #c5c5c5;
  border-radius: 50%;
  cursor: pointer;
  padding: 0 0.5rem;
  position: relative;
  margin: 0 0.5rem;
}

.dot:focus {
  outline: none;
}

.dot.active {
  background: $yellow;
}

.army-row {
  width: 30px;
  height: 30px;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  -webkit-transform: translateY(-50%);
  fill: #fff;
  cursor: pointer;
}

.army-row--left {
  left: 5px;
}

.army-row--right {
  left: auto;
  right: 5px;
}

.army-row--disabled {
  fill: rgba(255, 255, 255, 0.5);
}
</style>
