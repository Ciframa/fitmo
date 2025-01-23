<template>
  <div class="contact__wrapper">
    <div class="contact my-row">
      <div class="col-12-xs col-9-lg info__header__main">
        <h1>Kontakty</h1>
        <div class="contact__header__common">
          <a href="tel:+420702064265">+420 702 064 265</a>
          <span>po-pá: 8.00 - 17.30</span>
          <a href="mailto:info@fitmo.cz">info@fitmo.cz</a>
        </div>
        <ul class="my-row">
          <li class="col-12-xs col-6-md">
            <h3>Fakturační údaje</h3>
            <div class="my-row">
              <ul>
                <li>Martin Kadrmas</li>
                <li>Slatina 89</li>
                <li>566 01 Slatina</li>
                <li>Česká republika</li>
              </ul>
              <ul>
                <li>IČO: 08679100</li>
                <li>DIČ: CZ0001143263</li>
                <li class="bold">Neplátce DPH</li>
              </ul>
            </div>
          </li>
          <li class="col-12-xs col-6-md">
            <h3>Adresa pro vrácení zboží</h3>
            <ul>
              <li>Martin Kadrmas</li>
              <li>Slatina 89</li>
              <li>566 01 Slatina</li>
              <li>Česká republika</li>
            </ul>
          </li>
          <li class="col-12-xs col-6-md">
            <h3>Bankovní spojení</h3>
            <ul>
              <li>číslo účtu: 123-982020237/0100</li>
              <li>IBAN: CZ2501000001230982020237</li>
              <li>SWIFT/BIC: KOMBCZPPXXX</li>
            </ul>
          </li>
        </ul>
      </div>
      <side-bar-links></side-bar-links>
    </div>

    <form class="info__question">
      <h3>MÁTE DOTAZ?</h3>
      <div class="info__question__wrapper">
        <div class="my-row">
          <div>
            <label for="name">Jméno a příjmení</label>
            <input type="text" name="name" id="name" />
          </div>
          <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" />
          </div>
        </div>
        <textarea name="question" id="question"></textarea>
        <input type="submit" value="Odeslat" class="btn-yellow" />
      </div>
    </form>
    <custom-made></custom-made>
    <div class="info__form" id="customMadeForm">
      <h2>Mám zájem o výrobu na míru</h2>
      <form action="" class="my-row">
        <div class="col-12-xs col-6-md">
          <label for="">Jméno</label>
          <input type="text" />
        </div>
        <div class="col-12-xs col-6-md">
          <label for="">E-mail</label>
          <input type="text" />
        </div>
        <div class="col-12-xs col-6-md">
          <label for="">Tel. číslo</label>
          <input type="text" />
        </div>
        <div class="col-12-xs col-6-md upload">
          <label for="upload">Nákres</label>
          <label for="upload" class="btn">Vybrat soubor</label>
          <input type="file" id="upload" />
        </div>
        <div class="col-12-xs">
          <label for="">Popis překážky</label>
          <textarea></textarea>
        </div>
        <input type="submit" value="Odeslat" class="btn-yellow" />
      </form>
    </div>
  </div>
</template>

<script>
import CustomMade from "../../components/CustomMade.vue";
import SideBarLinks from "../../components/SideBarInfoLinks.vue";

export default {
  components: {
    CustomMade,
    SideBarLinks,
  },
};
</script>
<style lang="scss">
.info {
  h1 {
    font-size: 2.4rem;
  }

  &__header {
    padding: 0 6%;
    width: 100%;
    align-items: flex-start;
  }

  .customMade {
    width: 100%;
    margin: 0;
  }

  &__form {
    background: $white;
    width: 100%;

    h2 {
      font-size: 2.2rem;
    }

    form {
      width: 80%;
      margin: auto;
      max-width: 100rem;
      padding: 2rem;

      & > div {
        padding: 0 2rem;
        position: relative;

        label {
          width: 100%;
          display: block;
        }

        input,
        textarea {
          margin-bottom: 2rem;
          border: 1px solid $gray-second;
          padding: 0.75rem 1rem;
          font-size: 1.4rem;
          font-weight: 100;
          width: 100%;
        }

        textarea {
          padding: 1rem;
          width: 100%;
          resize: vertical;
          max-height: 40rem;
          border-radius: 2rem;
          min-height: 15rem;
          outline: none !important;
        }

        &.upload {
          input {
            opacity: 0;
            position: absolute;
            z-index: -1;
            top: -9999px;
          }

          .btn {
            margin-bottom: 2rem;
            background: $gray-second;
            color: $white;
            border-radius: 1rem;
            display: flex;
            max-width: 15rem;
            padding: 0;
            justify-content: center;
            align-items: center;
            margin-top: 1rem;
            cursor: pointer;

            &:hover {
              background: $yellow;
            }
          }
        }
      }

      .btn-yellow {
        margin: auto;
        padding: 1rem 4rem;
        font-size: 1.6rem;
      }
    }
  }

  &__question {
    display: flex;
    width: 100%;
    background: $black-second;
    min-height: 10rem;
    flex-direction: column;
    justify-content: center;
    padding: 5rem 0 3rem 0;

    h3 {
      color: $white;
    }

    &__wrapper {
      display: flex;
      flex-direction: column;
      gap: 2rem;
      width: 80%;
      margin: auto;
      max-width: 100rem;
      padding: 2rem;

      .my-row {
        & > div {
          display: flex;
          flex-direction: column;
          width: 50%;
          padding: 0 2rem;
        }
      }

      label {
        color: $white;
      }

      input[type="text"],
      input[type="email"] {
        height: 3rem;
        width: 100%;
        border-radius: 1rem;
        outline: 0;
        padding-left: 1rem;
        border: 0;
      }

      input[type="submit"] {
        margin: auto;
        padding: 1rem 4rem;
        font-size: 1.6rem;
      }

      textarea {
        padding: 1rem;
        width: calc(100% - 4rem);
        margin: auto;
        resize: vertical;
        max-height: 40rem;
        border-radius: 1.4rem;
        outline: none !important;
        min-height: 20rem;
      }
    }
  }
}

.icons {
  display: flex;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  align-items: flex-start;

  &__wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 0 1rem;

    &__item {
      padding: 1rem 0;

      > a {
        display: flex;
        align-items: center;
        padding: 3rem 2rem;
        border-radius: 2rem;
        background: $white;
        max-width: 22rem;
        max-height: 11rem;
        margin: 0;
        width: 100%;
        color: $black-headers;

        img {
          min-width: 5rem;
          height: 5rem;
          margin-right: 1.3rem;
        }

        span {
          font-weight: 800;
          font-size: 1.6rem;
          line-height: 1.8rem;
        }
      }
    }
  }

  h3 {
    font-size: 1.5rem;
    width: 100%;
    text-align: left;
  }
}

@media screen and (min-width: $screen-sm-min) {
  .info__question {
    input[type="search"] {
      width: 55%;
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .info__header {
    padding: 0 10% 4rem 10%;
  }

  .icons {
    margin-top: 5rem;

    &__wrapper__item {
      > a {
        img {
          min-width: 4.5rem;
          height: 4.5rem;
        }

        span {
          font-size: 1.3rem;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) and (max-width: $screen-lg-min - 1px) {
  .icons__wrapper {
    flex-wrap: nowrap;

    &__item {
      padding: 1rem 0;
      display: flex;

      &:nth-child(2) {
        justify-content: center;
      }

      &:nth-child(3) {
        justify-content: flex-end;
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) and (max-width: $screen-lg-min - 1px) {
  .icons__wrapper__item {
    > a {
      justify-content: center;
    }
  }
}

@media screen and (min-width: $screen-lg-min) {
  .info__header {
    &__main {
      padding-right: 5rem;
    }
  }

  .icons__wrapper__item {
    padding-left: 0;

    > a {
      img {
        width: 5rem;
        height: 5rem;
      }

      span {
        font-size: 1.4rem;
      }
    }
  }
}

.contact {
  padding: 0 10% 4rem 10%;
  background: $gray-third;

  &__wrapper {
    background: $gray-third;
  }

  &__header {
    &__common {
      display: flex;
      flex-wrap: wrap;
      margin: 2.2rem 0 5rem 0;
      align-items: center;
      color: $black-second;

      a {
        font-weight: 500;
        font-size: 2.5rem;

        &:first-child {
          margin-right: 1.5rem;
        }

        &:last-child {
          width: 100%;
          margin-top: 0.5rem;
        }
      }

      span {
        margin-top: 0.5rem;
        font-size: 1.6rem;
      }
    }
  }

  h1 {
    text-align: left;
    width: 100%;
  }

  ul.my-row {
    color: $black-headers;
    gap: 1rem;
    margin-bottom: 2rem;

    > li {
      display: flex;
      flex-direction: column;
    }

    .my-row,
    .column {
      display: flex;
      gap: 0 3rem;

      ul:first-child {
        margin-bottom: 0;
      }

      ul:last-child {
        margin-right: 2rem;
      }
    }

    h3 {
      text-align: left;
      font-weight: 700;
      font-size: 1.4rem;
    }

    li {
      font-size: 1.4rem;
      line-height: 2rem;

      .bold {
        font-weight: 500;
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .contact {
    ul.my-row {
      gap: 0;

      li {
        line-height: 2.2rem;
      }
    }

    ul {
      margin: 0.2rem 0 4rem 0;
    }
  }
}
</style>
