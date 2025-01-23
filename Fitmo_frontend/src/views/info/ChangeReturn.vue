<template>
  <div class="contact__wrapper">
    <div class="contact my-row">
      <div class="col-12-xs col-9-lg info__header__main">
        <h1>Výměna a vrácení zboží</h1>
        <ul class="my-row">
          <li class="col-12-xs">
            <div class="column">
              <p>
                Pokud Vám doručené zboží z nějakého důvodu nevyhovuje, zašlete
                nám zboží zpět.
              </p>
              <p>
                U nás to není žádný problém. Na výměnu zboží máte až 14 dní od
                převzetí, nicméně nemáme problém i s delší časovou lhůtou,
                spokojený zákazník je pro nás totiž na prvním místě.
              </p>
              <p>
                Pokud jste se rozhodli zboží vrátit, ujistěte se, že nepoškozené
                a bez větších známek používání. Dále Vás poprosíme, abyste zboží
                pečlivě zabalili a odeslali ho na níže uvedenou adresu.
              </p>
              <ul>
                <h3>Adresa pro zaslání zboží:</h3>
                <li>Fitmo.cz</li>
                <li>Martin Kadrmas</li>
                <li>Slatina 89</li>
                <li>566 01 Slatina</li>
                <li>Česká republika</li>
                <li>tel. +420 702 064 265</li>
                <li>e-mail: obchod@fitmo.cz</li>
              </ul>
              <p>
                V případě vrácení zboží, můžete využít předpřipravených
                formulářů, které nám v tištěné formě zašlete společně s
                balíčkem.
              </p>
              <div class="documents">
                <div class="my-row">
                  <a
                    class="document"
                    href="../../../assets/documents/Formulář%20pro%20odstoupení%20od%20smlouvy.docx"
                    download
                  >
                    <font-awesome-icon :icon="['far', 'file']" />
                    <span>Formulář pro odstoupení od smlouvy</span></a
                  >
                </div>
                <div class="my-row">
                  <a
                    class="document"
                    href="../../../assets/documents/Formulář pro uplatnění reklamace.docx"
                    download
                  >
                    <font-awesome-icon :icon="['far', 'file']" />
                    <span>Formulář pro uplatnění reklamace</span></a
                  >
                </div>
              </div>
              <p>
                Pokud máte jakýkoliv dotazy ohledně vrácení a výměny zboží, tak
                nás neváhejte kontaktovat a vše spolu vyřešíme.
              </p>
              <div class="contact__header__common">
                <a href="tel:+420702064265">+420 702 064 265</a>
                <span>po-pá: 8.00 - 17.30</span>
                <a target="_blank" href="mailto:info@fitmo.cz">info@fitmo.cz</a>
              </div>
            </div>
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
    <div class="info__form">
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
<style lang="scss" scoped>
.column {
  display: flex;
  flex-direction: column;
}

.my-row,
.column {
  > * {
    margin: 0.6rem 0;
  }
}

h1 {
  text-align: left;
  margin-bottom: 1rem;
}

.contact__header__common {
  margin-top: 2rem;
}

a.document {
  font-weight: 500;

  span {
    text-decoration: underline;
    padding-left: 0.6rem;
  }
}

.contact ul .documents {
  .my-row {
    gap: 0;
  }
}
</style>
