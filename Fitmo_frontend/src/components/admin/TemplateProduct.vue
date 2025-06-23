<template>
  <div
    class="templates"
    :style="{
      background:
        template.template.type !== 'bloky'
          ? template.template.color
          : 'transparent',
    }"
  >
    <div v-if="template.template.type === 'bloky'" class="bloky my-row">
      <template v-for="i in 6" :key="i">
        <div
          class="col-12-xs col-6-md col-4-lg column"
          v-if="template.template[`image${i}`]"
        >
          <div>
            <img
              :src="
                template.template.from !== 'db'
                  ? template.template[`image${i}`]?.tentativePath
                  : product.color_name
                  ? imageBasePath +
                    product.name +
                    '-' +
                    product.color_name +
                    '/' +
                    template.template[`image${i}`]
                  : product.variant
                  ? imageBasePath +
                    product.name +
                    '-' +
                    product.variant +
                    '/' +
                    template.template[`image${i}`]
                  : imageBasePath +
                    product.name +
                    '/' +
                    template.template[`image${i}`]
              "
            />
            <h3>{{ template.template[`txt${i}`] }}</h3>
            <p>{{ template.template[`subtxt${i}`] }}</p>
          </div>
        </div>
      </template>
    </div>

    <div
      v-if="
        template.template.type === 'fotkaText' ||
        template.template.type === 'textFotka'
      "
      class="my-row-wrapReverse"
      :class="{
        'my-row-reversed': template.template.type === 'textFotka',
        [template.template.type]: true,
      }"
    >
      <img
        class="col-12-xs col-6-lg"
        :src="
          template.template.from === 'db'
            ? product.color_name
              ? imageBasePath +
                product.name +
                '-' +
                product.color_name +
                '/' +
                template.template.image1
              : product.variant
              ? imageBasePath +
                product.name +
                '-' +
                product.variant +
                '/' +
                template.template.image1
              : imageBasePath + product.name + '/' + template.template.image1
            : template.template.image1?.tentativePath
        "
      />
      <div
        class="col-12-xs col-6-lg text"
        v-html="template.template.text"
      ></div>
    </div>

    <img
      v-if="template.template.type === 'jenFotka'"
      class="col-12-xs jenFotka"
      :src="
        template.template.from !== 'db'
          ? template.template.image1?.tentativePath
          : product.color_name
          ? imageBasePath +
            product.name +
            '-' +
            product.color_name +
            '/' +
            template.template.image1
          : product.variant
          ? imageBasePath +
            product.name +
            '-' +
            product.variant +
            '/' +
            template.template.image1
          : imageBasePath + product.name + '/' + template.template.image1
      "
    />
    <div
      v-if="template.template.type === 'jenText'"
      class="col-12-xs text jenText"
      v-html="template.template.text"
    ></div>
    <div class="textNaFotce" v-if="template.template.type === 'textNaFotce'">
      <div class="col-12-xs text" v-html="template.template.text"></div>
      <img
        class="col-12-xs"
        :src="
          template.template.from !== 'db'
            ? template.template.image1?.tentativePath
            : product.color_name
            ? imageBasePath +
              product.name +
              '-' +
              product.color_name +
              '/' +
              template.template.image1
            : product.variant
            ? imageBasePath +
              product.name +
              '-' +
              product.variant +
              '/' +
              template.template.image1
            : imageBasePath + product.name + '/' + template.template.image1
        "
      />
    </div>
    <div
      v-if="
        template.template.type === 'fotkyText' ||
        template.template.type === 'textFotky'
      "
      class="my-row-wrapReverse"
      :class="{
        'my-row-reversed': template.template.type === 'textFotky',
        [template.template.type]: true,
      }"
    >
      <div class="my-row-wrapReverse image-wrapper col-12-xs col-6-lg">
        <template v-for="i in 6" :key="i">
          <div class="col-4-xs column" v-if="template.template[`image${i}`]">
            <img
              :src="
                template.template.from !== 'db'
                  ? template.template[`image${i}`]?.tentativePath
                  : product.color_name
                  ? imageBasePath +
                    product.name +
                    '-' +
                    product.color_name +
                    '/' +
                    template.template[`image${i}`]
                  : product.variant
                  ? imageBasePath +
                    product.name +
                    '-' +
                    product.variant +
                    '/' +
                    template.template[`image${i}`]
                  : imageBasePath +
                    product.name +
                    '/' +
                    template.template[`image${i}`]
              "
            />
            <p>{{ template.template[`txt${i}`] }}</p>
          </div>
        </template>
      </div>
      <div
        class="col-12-xs col-6-lg text"
        v-html="template.template.text"
      ></div>
    </div>
    <div class="youtubeVideo" v-if="template.template.type === 'youtubeVideo'">
      <iframe
        :src="template.template.txt1"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
    </div>
  </div>
</template>
<!--
            <option value="textFotky">Text | Více fotek</option>
            <option value="fotkyText">Více fotek | Text</option>
            <option value="jenFotka">Jen fotka</option>
            <option value="jenText">Jen text</option>
-->
<script>
export default {
  props: {
    template: Object,
    product: Object,
    type: String,
  },
  components: {},
  data() {
    return {
      products: [],
      imageBasePath: `https://be.fitmo.cz/products/`,
    };
  },
  methods: {},

  mounted() {},
};
</script>
<style lang="scss">
li.ql-indent-1 {
  margin-left: 1.5em !important;
}

li.ql-indent-2 {
  margin-left: 3em;
}

li.ql-indent-3 {
  margin-left: 4.5em;
}

li.ql-indent-4 {
  margin-left: 6em;
}

li.ql-indent-5 {
  margin-left: 7.5em;
}

li.ql-indent-6 {
  margin-left: 9em;
}

li.ql-indent-7 {
  margin-left: 10.5em;
}

li {
}

.templates {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  background: $white;
  border-radius: 2rem;

  p,
  h2,
  h1,
  h3,
  h4,
  h5,
  h6 {
    text-align: left;
  }

  .my-row-reversed {
    flex-direction: row-reverse;
  }

  .my-row-wrapReverse {
    display: flex;
    flex-wrap: wrap-reverse;
  }

  li {
    text-align: left;
  }

  ul {
    padding-left: 2.5rem;
    list-style: disc;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  ol {
    padding-left: 2.5rem;
    list-style: decimal;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .jenText {
    padding: 2rem 5%;
  }

  .jenFotka {
    border-radius: 2rem;
  }

  .youtubeVideo {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 ratio (9 / 16 = 0.5625) */
    height: 0;
    overflow: hidden;
    width: 100%;
    border-radius: 2rem;

    iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }
  }

  .bloky {
    margin: auto;
    justify-content: center;

    > div > div {
      padding: 0.5rem 1rem;
    }

    img {
      box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
      margin: auto;
      aspect-ratio: 1/1;
      object-fit: cover;
      border-radius: 1.5rem;
    }

    p {
      text-align: center;
      line-height: 1.7rem;
    }

    h3 {
      padding: 1rem 0 0.2rem 0;
      text-align: center;
    }
  }

  .textFotky {
    align-items: center;
    padding: 5rem 5% 5rem 5%;

    &.my-row-reversed {
      .text {
        padding: 0;
        padding-right: 5%;
      }
    }

    .text {
      padding: 0;
      padding-left: 5%;
    }
  }

  .fotkaText,
  .textFotka {
    // padding: 0 5%;
    img {
      object-fit: scale-down;
      border-radius: 2rem;
      -webkit-box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
      -moz-box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
      box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
    }

    .text {
      padding: 3rem;
      justify-content: center;

      h2 {
        padding-top: 0;
      }
    }

    &.my-row-reversed {
      img {
      }
    }
  }

  &__wrapper {
    display: flex;
    gap: 2rem;
    flex-direction: column;
  }

  .text {
    display: flex;
    flex-direction: column;
    padding-top: 5rem;

    h3 {
      padding: 2.4rem 0 1rem 0;
    }

    p,
    li {
      font-size: 1.4rem !important;
      //max-width: 100rem;
    }

    li {
      max-width: 97.5rem;
    }

    & > div {
      width: 100%;
      display: flex;
      flex-wrap: wrap;

      img {
        object-fit: contain;
      }

      & > img {
        border-radius: 2rem;
      }

      &.my-row-reversed {
        flex-direction: row-reverse;
      }
    }

    &.textNaFotce {
      position: relative;

      .text {
        position: absolute;
      }
    }
  }

  .image-wrapper {
    display: flex;
    flex-wrap: wrap;

    & > div {
      padding: 1rem 0.5rem;
    }

    img {
      padding: 0.4rem;
      aspect-ratio: 1/1;
      object-fit: cover;
      border-radius: 2rem;
      border-radius: 2rem 2rem 0 0;
      margin: auto;
      background: $gray-third;
    }

    p {
      background: $gray-third;
      border-radius: 0 0 2rem 2rem;
      padding-bottom: 0.8rem;
      font-weight: 600;
      font-size: 1.3rem;
    }
  }

  .column {
    text-align: center;
  }
}

.ql-align-center {
  text-align: center !important;
  width: 100%;
}

.ql-align-right {
  text-align: right !important;
}

.ql-align-justify {
  text-align: justify !important;
}

.editor {
  h2 {
    font-size: 2rem !important;
    text-align: left;
  }

  h3 {
    font-size: 2rem !important;
    text-align: left;
  }
}
</style>
