<template>
  <div class="registration my-row">
    <div class="col-12-xs col-6-md">
      <h1>Registrace</h1>
      <form action="" @submit.prevent="registration(formData)">
        <h2>Osobní údaje</h2>
        <label for="name">Jméno a příjmení*</label>
        <input type="text" name="" id="name" required v-model="formData.name" />
        <label for="phoneNumber">Telefonní číslo*</label>
        <input
          type="tel"
          name=""
          id="phoneNumber"
          required
          v-model="formData.phoneNumber"
        />
        <label for="emailReg">Email*</label>
        <input
          type="email"
          name=""
          id="emailReg"
          required
          v-model="formData.email"
          :class="{ invalid: this.formData.responseAnswer != '' }"
        />
        <span>
          {{ this.formData.responseAnswer }}
        </span>
        <label for="passwordReg">Vytvořte heslo*</label>
        <input
          type="password"
          id="passwordReg"
          required
          v-model="formData.password"
          pattern=".{8,}$"
          @input="passwordRequirementsCheck(formData.password)"
        />
        <div class="regex">
          <span
            :class="{
              unfulfilled: !formData.passwordStrength >= 1,
              fulfilled: formData.passwordStrength >= 1,
            }"
            v-show="formData.password?.length > 0"
            >Heslo musí obsahovat minimálně 8 znaků</span
          >
          <div class="regex__bar" v-if="formData.passwordStrength >= 1">
            <div :class="'regex__bar' + formData.passwordStrength"></div>
            <span>{{
              formData.passwordStrengthText[formData.passwordStrength - 1]
            }}</span>
          </div>
        </div>
        <label for="passwordRegCheck">Potvrdit heslo*</label>
        <input
          type="password"
          name=""
          id="passwordRegCheck"
          required
          v-model="formData.passwordCheck"
        />
        <span class="quote">{{ formData.quote }}</span>

        <div class="billing_information">
          <h2>Fakturační a dodací údaje</h2>
          <div>
            <label for="street">Ulice a číslo popisné</label>
            <input type="text" name="" id="street" />
          </div>

          <div>
            <label for="town">Město</label>
            <input type="text" name="" id="town" />
          </div>
          <div>
            <label for="zip_code">PSČ</label>
            <input type="text" name="" id="zip_code" />
          </div>
          <input
            type="checkbox"
            name=""
            id="identicalAdresses"
            :checked="this.formData.identicalAdresses"
            v-model="this.formData.identicalAdresses"
          />
          <label for="identicalAdresses" class="billing_information__check">
            <font-awesome-icon :icon="['fa', 'check']" />
            Fakturační údaje jsou stejné jako dodací</label
          >
        </div>

        <div
          class="billing_information"
          v-if="!this.formData.identicalAdresses"
        >
          <h2>Dodací údaje</h2>
          <div>
            <label for="street">Ulice a číslo popisné</label>
            <input type="text" name="" id="street" />
          </div>
          <div>
            <label for="town">Město</label>
            <input type="text" name="" id="town" />
          </div>
          <div>
            <label for="zip_code">PSČ</label>
            <input type="text" name="" id="zip_code" />
          </div>
          <input
            type="checkbox"
            name=""
            id="identicalAdresses"
            :checked="this.formData.identicalAdresses"
            v-model="this.formData.identicalAdresses"
          />
        </div>

        <div class="options">
          <div class="my-row">
            <input type="checkbox" name="Agrees" id="first" />
            <label for="first">
              <font-awesome-icon :icon="['fa', 'check']" />
              Chci odebírat akční nabídky a novinky</label
            >
          </div>
          <div class="my-row">
            <input type="checkbox" name="Agrees" id="second" />
            <label for="second">
              <font-awesome-icon :icon="['fa', 'check']" />
              Souhlasím s obchodními podmínkami</label
            >
          </div>
          <div class="my-row">
            <input type="checkbox" name="Agrees" id="third" checked />
            <label for="third">
              <font-awesome-icon :icon="['fa', 'check']" />
              Souhlasím se zpracování osobních údajů</label
            >
          </div>
        </div>
        <input type="submit" value="Registrovat se" class="btn-yellow" />
      </form>
    </div>

    <div class="col-12-xs col-6-md">
      <div class="registration__info">
        <h2>Proč se registrovat?</h2>
        <article>
          <p>Ještě nemáš vytvořený účet?</p>
          <p>Zaregistruj se a získej spousty výhod</p>
        </article>
        <ul>
          <li>
            <img src="../../public/assets/icons/check_transparent.svg" alt="" />
            <span>Rychlejší nakupování</span>
          </li>
          <li>
            <img src="../../public/assets/icons/check_transparent.svg" alt="" />
            <span>Historie všech svých objednávek</span>
          </li>

          <li>
            <img src="../../public/assets/icons/check_transparent.svg" alt="" />
            <span>Nemusíš vyplňovat své údaje</span>
          </li>
          <li>
            <img src="../../public/assets/icons/check_transparent.svg" alt="" />
            <span>FITMO novinky z první ruky</span>
          </li>
        </ul>
      </div>
      <div class="registration__buttons">
        <h2>Registrace přes sociální sítě</h2>
        <a class="btn-blue" href="#">Registrovat se přes Facebook</a>
        <a class="btn-google" href="#">Registrovat se přes Google</a>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "../api";

export default {
  components: {},
  data() {
    return {
      formData: {
        // identicalAdresses: true,
        //passwordStrengthText: ["slabé", "střední", "silné"],
        //passwordStrength: 0,

        email: "marekcifra@seznam.cz",
        identicalAdresses: true,
        name: "aFjm",
        password: "Markusius1",
        passwordCheck: "Markusius1",
        passwordStrength: 0,
        passwordStrengthText: [("slabé", "střední", "silné")],
        phoneNumber: "fawfa",
        quote: "",
        responseAnswer: "",
      },
    };
  },
  /**/
  methods: {
    registration(userData) {
      if (userData.password != userData.passwordCheck) {
        this.formData.quote = "Hesla se neshodují";
        return;
      } else {
        this.formData.quote = "";

        axios
          .post("api/register", { userData })
          .then((response) => {
            if (response.status == 201) {
              console.log("Úspěch");
            }
          })
          .catch((error) => {
            if (error.response.status === 409) {
              this.formData.responseAnswer =
                "Email je již používaný, prosíme použijte jiný";
            }
          });
      }
    },
    passwordRequirementsCheck(password) {
      let passwordEightCharacters = /.{8,}/.test(password);
      let passwordOneCapital = /.*[A-Z].*/.test(password);
      let passwordOneNumber = /.*[0-9].*/.test(password);

      if (passwordEightCharacters) {
        this.formData.passwordStrength = 1;

        if (passwordOneCapital || passwordOneNumber) {
          this.formData.passwordStrength = 2;
        }
        if (passwordOneCapital && passwordOneNumber) {
          this.formData.passwordStrength = 3;
        }
      } else {
        this.formData.passwordStrength = 0;
      }
    },
  },
};
</script>
<style lang="scss">
.billing_information {
  display: flex;
  margin-top: 1rem;

  input[type="checkbox"] {
    padding: 0;

    &:checked + label svg {
      background: $yellow;
      border-color: $yellow;
    }
  }

  &__check {
    display: flex;
    align-items: center;
    white-space: nowrap;
    font-size: 1.1rem;
    line-height: 2.2rem;
    margin-top: 0;

    svg {
      margin-right: 0.6rem;
      border: 1px solid #cac8c8;
      padding: 0.1rem;
      border-radius: 0.5rem;
      background: white;
      color: #ffffff;
      aspect-ratio: 1 / 1;
    }
  }
}

.registration {
  margin: auto;

  & > div:first-child {
    padding-left: 5%;
    padding-right: 5%;
  }

  & > div:last-child {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  h1 {
    text-align: left;
    margin-bottom: 1rem;
  }

  h2 {
    text-align: left;
    font-size: 2rem;
    width: 100%;
  }

  form {
    display: flex;
    flex-wrap: wrap;

    .quote {
      font-size: 1.6rem;
      color: red;
    }

    h2 {
      font-size: 1.8rem;
      margin-top: 2rem;
    }

    .regex {
      margin-top: 0.4rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      width: 100%;

      &__bar {
        align-items: center;
        display: flex;

        span {
          min-width: 4.2rem;
          font-weight: 400;
          text-align: right;
          margin-right: 0.5rem;
        }

        div {
          min-width: 10rem;
          display: block;
          height: 1.2rem;
          background: $gray-third;
          border-radius: 1rem;
          position: relative;
          margin-right: 2rem;
        }

        &1 {
          &::before {
            content: "";
            height: 100%;
            width: 33%;
            display: block;
            background: red;
            border-radius: 1rem;
          }
        }

        &2 {
          &::before {
            content: "";
            height: 100%;
            width: 66%;
            display: block;
            background: orange;
            border-radius: 1rem;
          }
        }

        &3 {
          &::after {
            content: "";
            height: 100%;
            width: 100%;
            display: block;
            background: green;
            border-radius: 1rem;
          }
        }
      }

      span {
        width: unset;
      }

      .fulfilled {
        color: lightgreen;
      }

      .unfulfilled {
        color: red;
      }
    }

    .billing_information {
      width: 100%;
      display: flex;
      flex-wrap: wrap;

      div {
        margin-top: 1.2rem;
        width: 100%;

        &:nth-child(5) {
          width: 9rem;
          margin-left: 2rem;
        }

        input {
        }
      }

      &__check {
        padding-top: 1rem;
      }
    }

    label {
      width: 100%;
      font-size: 1.4rem;
      margin-top: 1.2rem;
      display: block;
    }

    span {
      width: 100%;
      font-size: 1.1rem;
    }

    input {
      width: 100%;
      padding: 1rem;
    }

    input[type="checkbox"] {
      padding: 0;

      &:checked + label svg {
        background: $yellow;
        border-color: $yellow;
      }
    }

    .options {
      margin: 1.5rem 0 2.5rem 0;
      width: 100%;
    }

    .options,
    .billing_information {
      label {
        display: flex;
        align-items: center;
        white-space: nowrap;
        font-size: 1.1rem;
        line-height: 2.2rem;
        margin-top: 0;
      }

      svg {
        margin-right: 0.6rem;
        border: 1px solid $gray-second;
        padding: 0.1rem;
        border-radius: 0.5rem;
        color: $white;
      }
    }

    input[type="submit"] {
      padding: 0.75rem 8rem;
      line-height: 2.6rem;
      font-size: 1.6rem;
      cursor: pointer;
    }
  }

  &__info {
    background: $gray-third;
    padding: 3.5rem 10% 4rem 10%;
    width: 100%;
    color: $black-headers;
    margin-top: 2rem;

    article {
      line-height: 2rem;
      margin: 1rem 0;

      p {
        font-size: 1.5rem;
      }
    }

    ul {
      margin: 3rem 0 2rem 0;

      li {
        display: flex;
        align-items: center;
        font-size: 1.5rem;
        margin-bottom: 1.7rem;

        span {
          line-height: 1.8rem;
        }

        img {
          height: 1.7rem;
          margin-right: 1.5rem;
        }
      }
    }
  }

  &__buttons {
    width: 80%;
    margin: 2rem 0 0 0;
    max-width: 38rem;

    h2 {
      font-size: 1.6rem;
      font-weight: 600;
    }
  }

  .btn-blue,
  .btn-google {
    min-width: 90%;
    width: 90%;
    text-decoration: none;
    font-weight: 100;
    font-size: 1.6rem;
    white-space: nowrap;
    padding: 0.75rem;
    line-height: 2.6rem;
    margin: 1rem 0;
  }

  .btn-blue {
    margin-top: 0.5rem;
  }

  .btn-google {
    margin-bottom: 0;
  }
}

@media screen and (max-width: $screen-sm-min - 1px) {
  .registration {
    form input[type="submit"],
    &__buttons .btn-blue,
    &__buttons .btn-google {
      width: 100%;
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .registration {
    margin-bottom: 7rem;

    &__info {
      border-radius: 1.5rem;
      max-width: 45rem;
    }

    & > div:last-child {
      padding-right: 5%;
    }
  }
}

@media screen and (max-width: $screen-md-min - 1px) {
  .registration {
    input[type="submit"] {
      margin-bottom: 2.5rem;
    }

    & > div:last-child {
      display: flex;
      flex-direction: column-reverse;
    }

    &__buttons {
      width: 80%;
      margin: 0 0 3.3rem 10%;
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .registration {
    &__info {
    }
  }
}
</style>
