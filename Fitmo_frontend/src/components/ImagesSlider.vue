<template>
  <div class="images-wrapper my-row">
    <div ref="slider" class="keen-slider">
      <img
        class="keen-slider__slide"
        v-for="image in this.images"
        :key="image.id"
        :src="
          colorName
            ? imageBasePath + productName + '-' + colorName + '/' + image
            : productVariant
            ? imageBasePath + productName + '-' + productVariant + '/' + image
            : imageBasePath + productName + '/' + image
        "
        alt=""
      />
    </div>
    <div ref="thumbnail" class="keen-slider thumbnail">
      <img
        class="keen-slider__slide"
        v-for="image in this.images"
        :key="image.id"
        :src="
          colorName
            ? imageBasePath + productName + '-' + colorName + '/' + image
            : productVariant
            ? imageBasePath + productName + '-' + productVariant + '/' + image
            : imageBasePath + productName + '/' + image
        "
        alt=""
      />
    </div>
  </div>
</template>

<script>
import "keen-slider/keen-slider.min.css";
import KeenSlider from "keen-slider";

export default {
  props: {
    images: Object,
    imageBasePath: String,
    productName: String,
    productVariant: String,
    colorName: String,
  },
  name: "Slider",
  mounted() {
    this.slider = new KeenSlider(this.$refs.slider, {
      slides: {
        perView: 1,
      },
    });
    this.thumbnail = new KeenSlider(
      this.$refs.thumbnail,
      {
        initial: 0,
        slides: {
          perView: 6,
          spacing: 10,
        },
        breakpoints: {
          "(min-width: 1px)": {
            slides: {
              perView: 3, // Adjust for smaller screens
              spacing: 5,
            },
          },
          "(min-width: 576px)": {
            slides: {
              perView: 4, // Adjust for very small screens
              spacing: 10,
            },
          },
          "(min-width: 768px)": {
            slides: {
              perView: 5, // Adjust for very small screens
              spacing: 10,
            },
          },
          "(min-width: 992px)": {
            slides: {
              perView: 4, // Adjust for very small screens
              spacing: 10,
            },
          },
          "(min-width: 1200px)": {
            slides: {
              perView: 5, // Adjust for very small screens
              spacing: 10,
            },
          },
          "(min-width: 1600px)": {
            slides: {
              perView: 6, // Adjust for very small screens
              spacing: 10,
            },
          },
        },
      },
      [ThumbnailPlugin(this.slider)]
    );
  },
  beforeDestroy() {
    if (this.slider) this.slider.destroy();
    if (this.thumbnail) this.thumbnail.destroy();
  },
};

function ThumbnailPlugin(main) {
  return (slider) => {
    function removeActive() {
      slider.slides.forEach((slide) => {
        slide.classList.remove("active");
      });
    }

    function addActive(idx) {
      slider.slides[idx].classList.add("active");
    }

    function addClickEvents() {
      slider.slides.forEach((slide, idx) => {
        slide.addEventListener("click", () => {
          main.moveToIdx(idx);
        });
      });
    }

    slider.on("created", () => {
      addActive(slider.track.details.rel);
      addClickEvents();
      main.on("animationStarted", (main) => {
        removeActive();
        const next = main.animator.targetIdx || 0;
        addActive(main.track.absToRel(next));
        slider.moveToIdx(Math.min(slider.track.details.maxIdx, next));
      });
    });
  };
}
</script>

<style lang="scss">
[class^="number-slide"],
[class*=" number-slide"] {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  padding: 0 !important;
  margin: 0 !important;
  height: auto !important;
  min-height: unset !important;
  max-height: unset !important;
}

@media screen and (min-width: $screen-lg-min) {
  .thumbnail {
    margin: 1rem 0;
  }
}

.thumbnail .keen-slider__slide {
  object-fit: scale-down;
  padding: 0;
  font-size: 30px;
  margin-top: 10px;
  //height: 100px !important;
  //width: 100px !important;
  //min-width: 100px !important;
  //min-height: unset !important;
}

.thumbnail .keen-slider__slide {
  cursor: pointer;
}

.thumbnail .keen-slider__slide.active {
  border: 2px solid $yellow;
}

.keen-slider {
  min-height: fit-content;
}

.keen-slider:not([data-keen-slider-disabled]) .keen-slider__slide {
  height: auto;
  object-fit: inherit;
}
</style>
