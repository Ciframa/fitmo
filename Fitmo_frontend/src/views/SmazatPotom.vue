<template>
  <div>
    <table>
      <tr>
        <th>Jméno:</th>
        <th>Počet killů:</th>
        <th>Kdy zaznamenal svůj rekord:</th>
      </tr>

      <tr v-for="kill in kills" :key="kill.id">
        <td>{{ kill.name }}</td>
        <input type="text" v-model="kill.kills" @change="submit(kill)" />
        <td>{{ kill.updated_at }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
import axios from "../api";

export default {
  data() {
    return {
      kills: [],
    };
  },
  methods: {
    async getKills() {
      try {
        const response = await axios.get("/api/fonko");
        this.kills = response.data;
        this.kills.map((item) => {
          let date = new Date(item.updated_at);

          return (item.updated_at =
            date.getDay() +
            5 +
            "." +
            (date.getMonth() + 1) +
            "." +
            date.getFullYear());
        });
      } catch (error) {
        console.log(error);
      }
    },

    submit(hihi) {
      axios
        .put("/api/fonko/" + hihi.id, { hihi })
        .then((response) => {
          if (response.status == 200) {
            console.log("Úspěch");
            this.$emit(this.getKills());
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getKills();
  },
};
</script>

<style lang="scss" scoped>
div {
  width: 80%;
  margin: auto;
  justify-content: center;
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  margin-bottom: 5rem;
}
th {
  background: $gray-third;
}

table,
tr {
  width: 100%;
  border-collapse: collapse;
}
th,
td {
  width: 33%;
  padding: 1rem;
  border: 1px solid $black-headers;
  display: flex;
  align-items: center;
}
button {
  margin-top: 3rem;
}
h2 {
  margin-top: 7rem;
}
input {
  width: 33% !important;
  height: 100%;
  border-radius: 0 !important;
  padding: 2rem !important;
  display: flex;
  align-items: center;
}
tr {
  display: flex;
  flex-wrap: wrap;

  &:nth-child(2) td:first-child {
    font-weight: 1000;
    background: $yellow;
  }
  &:nth-child(3) td:first-child {
    font-weight: 600;
    background: silver;
  }
  &:nth-child(4) td:first-child {
    font-weight: 500;
    background: burlywood;
  }
}
</style>
