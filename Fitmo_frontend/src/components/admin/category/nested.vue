<template>
  <draggable
    class="dragArea"
    tag="ul"
    :list="categories"
    :group="{ name: 'g1' }"
    item-key="name"
  >
    <template #item="{ element }">
      <li
        :style="{
          marginLeft: `${depth * 20}px`,
          backgroundColor: `rgba(30, 144, 255, ${Math.min(depth * 0.15, 1)})`,
          listStyleType: 'none',
        }"
      >
        <div
          class="row"
          :style="{
            backgroundColor: `rgba(30, 144, 255, ${Math.min(depth * 0.05, 1)})`,
          }"
        >
          <div
            :style="{
              display: 'flex',
              alignSelf: 'center',
              margin: '0 0.5rem 0 0.4rem',
            }"
          >
            {{ element.id_parent }}
          </div>
          <img
            v-if="element?.image?.tentativePath || element.image_path"
            :src="
              element?.image?.tentativePath ??
              this.imagesBasePath + element.image_path ??
              'No image'
            "
          />
          <input type="text" v-model="element.name" />
          <input
            v-if="element.id !== 'Nezařazeny'"
            type="file"
            @change="(e) => addPhoto(e, element)"
            id="upload"
            ref="upload"
          />
          <input
            v-if="element.id !== 'Nezařazeny' && element?.status !== 'created'"
            type="submit"
            v-on:click="updateCategory(element)"
            value="Upravit kategorii"
            class="btn-yellow"
          />
          <input
            v-if="element.id !== 'Nezařazeny' && element?.status !== 'created'"
            type="submit"
            v-on:click="deleteCategory(element)"
            value="Smazat kategorii"
            class="btn-red"
          />
          <div v-if="element?.status === 'created'" class="btn-green">
            NOVÁ KATEGORIE
          </div>
        </div>
        <nested-draggable :categories="element.children" />
      </li>
    </template>
  </draggable>
</template>
<script>
import axios from "../../../api";
import draggable from "vuedraggable";

export default {
  props: {
    categories: {
      required: true,
      type: Array,
    },
  },

  computed: {},
  components: {
    draggable,
  },
  name: "nested-draggable",
  data() {
    return {
      depth: 2,
      imagesBasePath: `https://be.fitmo.cz/categories/`,
    };
  },
  watch: {
    categories: {
      handler: function (val) {},
      deep: true,
    },
  },
  methods: {
    async updateCategory(category) {
      await axios
        .post("/api/category/" + category.id, category, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((response) => {
          if (response.status == 200) {
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
          } else {
            this.$snackbar.add({
              type: "error",
              text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
            });
          }
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
        });
    },

    async deleteCategory(category) {
      await axios
        .delete("/api/category/" + category.id)
        .then((response) => {
          if (response.status == 200) {
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
            console.log("Emitting get-categories event");
          } else {
            this.$snackbar.add({
              type: "error",
              text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
            });
          }
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
        });
    },

    addPhoto(event, category) {
      const uploadedFile = event.target.files[0];
      // Get the selected file
      if (uploadedFile) {
        var reader = new FileReader();
        reader.readAsDataURL(uploadedFile);

        reader.onload = () => {
          // Use arrow function here
          let file = new File([reader.result], uploadedFile.name);
          let newPhoto = {
            file: uploadedFile,
            image_path: uploadedFile.name,
            tentativePath: URL.createObjectURL(uploadedFile),
          };
          category.image = newPhoto;
        };

        reader.onerror = (error) => {
          // Use arrow function here
          console.log("Error: ", error);
        };
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.dragArea {
  input {
    width: unset !important;
  }

  img {
    width: 50px;
    height: 50px;
  }
}
</style>
