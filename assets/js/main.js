jQuery(document).ready(function ($) {
  $spec = {
    "Drvna industrija i šumarstvo": "spec1",
    "Elektro-hemijska industrija": "spec1",
    Energetika: "spec2",
    "Finansijske djelatnosti i osiguranje": "spec3",
    Građevinarstvo: "spec4",
    "Informaciono-komunikacione tehnologije": "spec5",
    "Komunalne i uslužne djelatnosti": "spec6",
    "Metalurgija i prerada metala": "spec1",
    "Poljoprivreda, ribarstvo, prehrambena i duvanska industrija": "spec7",
    "Saobraćaj i veze": "spec8",
    "Industrija tekstila, kože i obuće": "spec1",
    Trgovina: "spec9",
    "Turizam i ugostiteljstvo": "spec10",
    Štampanje: "spec1",
    "Ništa od navedenog": {
      "Kako se rukovodstvo odnosi prema inovacijama u kompaniji? (Možete izabrati jedan odgovor)":
        "spec11",
      "Koje digitalne tehnologije trenutno koristite u kompaniji? (Možete izabrati jedan ili više odgovora)":
        "spec16",
      "Koje od usluga eUprave koristite? (Možete izabrati jedan ili više odgovora)":
        "spec17",
      "Na koji način pratite i upravljate inovacijama? (Možete izabrati jedan ili više odgovora)":
        "spec18",
      "Kako dizajnirate proizvode? (Možete izabrati jedan ili više odgovora)":
        "spec20",
      "Kako se provodi obuka za pripremu zaposlenih za digitalnu transformaciju? (Možete izabrati jedan ili više odgovora)":
        "spec21",
      "Da li ste učestvovali u nekim od programa podrške digitalnoj transformaciji privrednih društava? (Možete izabrati jedan ili više odgovora)":
        "spec24",
    },
    "Bolja informisanost rukovodstva.": "spec12",
    "Manje grešaka u radu zaposlenih.": "spec12",
    "Bolja komunikacija unutar kolektiva.": "spec12",
    "Efikasan sistem kreiranja, praćenja i čuvanja dokumenata.": "spec13",
    "Korišćenje novih kanala komunikacije sa kupcima.": "spec14",
    "Jačanje brenda.": "spec14",
    "Povećanje stepena fleksibilnosti u dizajniranju ponude.": "spec14",
    "Sistemski pristup on-line marketingu.": "spec14",
    "Sajber bezbjednost.": "spec15",
    "Ručno pomoću tabela upravljamo i pratimo aktivnosti inoviranja.": "spec18",
    "Procesi nisu automatizovani i obavljaju ih samo zaposleni.": "spec19",
    "Procesi su djelimično automatizovani, ali i dalje zahtjevaju značajnu intervenciju zaposlenih.":
      "spec19",
    "Kompjuterski potpomognut dizajn (CAD - Computer Added Design) se ne koristi u dizajnu proizvoda.":
      "spec20",
    "Proizvodi su dizajnirani pomoću CAD-a, a izrada prototipa se vrši pomoću 3D skenera i štampača.":
      "spec20",
    "Ne organizujemo obuke zaposlenih.": "spec21",
    "Prodaja se obavlja samo tradicionalnim kanalima, a tehnološki nivo ne dozvoljava personalizaciju sadržaja, kanala komunikacije, ponude i proizvoda.":
      "spec14",
    "Ne prikupljamo, niti koristimo podatke.": "spec22",
    "Podatke prikupljamo i koristimo za segmentaciju tržišta i stvaranje personalizovanog odnosa sa kupcima.":
      "spec22",
    "Podatke prikupljamo i koristimo za efikasnije upravljanje operacijama i optimizaciju poslovnih procesa.":
      "spec22",
    "Podatke prikupljamo i koristimo za razvoj novih proizvoda i usluga.":
      "spec22",
    "Nedostatak finansijskih sredstava.": "spec23",
    "Nedostatak vizije i sveobuhvatne digitalne strategije.": "spec24",
    "Ne primjenjujemo digitalnu transformaciju u kompaniji.": "spec24",
  };

  function populateSpec($spec) {
    $(".tab-content").each(function () {
      $allInputs = $(this).find(".field-wrap input");
      $allInputs.each(function () {
        if (
          $(this).attr("type") !== "text" &&
          $(this).attr("type") !== "email"
        ) {
          if ($(this).val("Ništa od navedenog")) {
            $label = $(this).parents(".field-wrap").children("label").text();
            if ($spec["Ništa od navedenog"][$label]) {
              $specValue = $spec["Ništa od navedenog"][$label];
              $specString = JSON.stringify($specValue);
              $specString = $specString.replaceAll('"', "");
              $(this).attr("data-spec", $specString);
            }
          } else if ($spec[$(this).attr("value")]) {
            $(this).attr("data-spec", $spec[$(this).attr("value")]);
          } else if ($spec[$(this).attr("data-quiz-value")]) {
            $(this).attr("data-spec", $spec[$(this).attr("data-quiz-value")]);
          }
        }
      });
      $allSelects = $(this).find(".field-wrap option");
      $allSelects.each(function () {
        if ($spec[$(this).attr("data-quiz-value")]) {
          $(this).attr("data-spec", $spec[$(this).attr("value")]);
        } else if ($spec[$(this).attr("data-quiz-value")]) {
          $(this).attr("data-spec", $spec[$(this).attr("data-quiz-value")]);
        }
      });
    });
  }

  populateSpec($spec);

  $("#message-button").click(function () {
    $(".message-wrap").hide();
  });

  $(".tablinks").click(function (event) {
    var tabClass = $(this).attr("class");
    var tabData = $(this).attr("data-wrapper");
    if (!tabClass.includes("active")) {
      var tabLinkActive = $(".tablinks.active");
      var tabLinkActiveData = $(".tablinks.active").attr("data-wrapper");
      tabLinkActive.removeClass("active");
      $("." + tabLinkActiveData).removeClass("active");
      $(this).addClass("active");
      $("." + tabData)
        .addClass("active")
        .show();
    }

    $tabs = $(".tablinks");
    $currentTab = $(".tablinks.active");
    if ($tabs.last().text() === $currentTab.text()) {
      $(".submit-quiz").text("Pošalji");
    } else if ($(".submit-quiz").text() !== "Nastavi") {
      $(".submit-quiz").text("Nastavi");
    }
  });

  $(".input-wrap.radio > label").click(function () {
    $('.input-wrap.radio > label input[type="radio"]').attr("checked", false);
    $(this).children('input[type="radio"]').attr("checked", true);
  });

  $(".submit-quiz").click(function () {
    if ($(this).text() === "Pošalji") {
      quizData();
    } else {
      manipulateButton($(this));
    }
  });

  function manipulateButton($this) {
    const scrollToEl = $(".quiz");
    $tabs = $(".tablinks");
    $currentTab = $(".tablinks.active");
    $nextTab = $(".tablinks.active").next(".tablinks");
    if ($nextTab.length == 0 || $tabs.last() === $currentTab) {
      $nextTab = $tabs.first();
    }

    $currentTab.removeClass("active");
    $nextTab.addClass("active");
    $currentTabData = $currentTab.attr("data-wrapper");
    var tabLinkActiveData = $(".tablinks.active").attr("data-wrapper");
    if ($currentTabData != tabLinkActiveData) {
      $("." + $currentTabData).removeClass("active");
      $("." + tabLinkActiveData).addClass("active");
    }

    $currentTab = $(".tablinks.active");
    if ($tabs.last().text() === $currentTab.text()) {
      $this.text("Pošalji");
    } else if ($this.text() !== "Nastavi") {
      $this.text("Nastavi");
    }

    $("html").animate(
      {
        scrollTop: scrollToEl.offset().top,
      },
      800 //speed
    );
  }

  function quizData() {
    //TODO: Check if all questions are answered, if not, show error message
    // get each tab and check if all questions are answered
    var allQuestionsAnswered = false;
    $finalChoices = [];
    $tabContent = $(".tab-content .field-wrap");
    $(".tab-content").each(function () {
      $tabFields = $(this).find(".field-wrap"); // get all fields in tab
      $tabFieldsCount = $tabFields.length; // count all fields in tab
      $currentTab = $(this).attr("class");
      $currentTab = $currentTab.replace("tab-content", "");
      $currentTab = $currentTab.replace("active", "");
      $selectedChoices = [];
      $tabFields.each(function (index, input) {
        $input = $(input).find("input:checked");
        $input.each(function () {
          $label = $(this).parents(".field-wrap").children("label").text();
          $currentChoice = {
            area: $currentTab,
            label: $label,
            data: {
              dataValue: $(this).attr("data-quiz-value"),
              dataSpec: $(this).attr("data-spec"),
            },
          };
          $(this).parents(".field-wrap").addClass("answered");

          $selectedChoices.push($currentChoice);
        });

        $select = $(input).find("select");
        $select.each(function () {
          $label = $(this).parents(".field-wrap").children("label").text();
          $currentChoice = {
            area: $currentTab,
            label: $label,
            data: {
              dataValue: $(this).attr("data-quiz-value"),
              dataSpec: $(this).attr("data-spec"),
            },
          };
          $(this).parents(".field-wrap").addClass("answered");
          $selectedChoices.push($currentChoice);
        });

        $text = $(input).find("input[type='text']");
        $text.each(function () {
          $label = $(this).parents(".field-wrap").children("label").text();
          if ($(this).val() !== "") {
            $currentChoice = {
              area: $currentTab,
              label: $label,
              data: {
                dataValue: $(this).attr("data-quiz-value"),
                dataSpec: $(this).attr("data-spec"),
                text: $(this).val(),
              },
            };
            $(this).parents(".field-wrap").addClass("answered");
            $selectedChoices.push($currentChoice);
          }
        });

        $email = $(input).find("input[type='email']");
        $email.each(function () {
          $label = $(this).parents(".field-wrap").children("label").text();
          if ($(this).val() !== "") {
            $currentChoice = {
              area: $currentTab,
              label: $label,
              data: {
                dataValue: $(this).attr("data-quiz-value"),
                dataSpec: $(this).attr("data-spec"),
                text: $(this).val(),
              },
            };
            $(this).parents(".field-wrap").addClass("answered");
            $selectedChoices.push($currentChoice);
          }
        });
      });
      $finalChoices.push($selectedChoices);
    });

    $getAnswered = $(".answered");
    if ($getAnswered.length != $tabContent.length) {
      if (!$(".checkbox-confirmation").is(":checked")) {
        $text = "Morate prihvatiti uslove korišćenja kako bi poslali upitnik.";
        $(".message").text($text);
        $(".message-wrap").show();
      } else {
        $text = "Niste odgovorili na sva pitanja";
        $(".message").text($text);
        $(".message-wrap").show();
      }
    } else {
      allQuestionsAnswered = true;
    }

    if (allQuestionsAnswered === true) {
      let ajaxUrl = inndigit_ajax_object.ajax_url;
      $.ajax({
        type: "post",
        url: ajaxUrl,
        data: {
          action: "get_quiz_data", // the action to fire in the server
          data: $finalChoices, // any JS object
        },
        complete: function (response) {
          console.log(JSON.parse(response.responseText).data);
        },
      });
    }
  }

  //TODO: prikazati success message
});
