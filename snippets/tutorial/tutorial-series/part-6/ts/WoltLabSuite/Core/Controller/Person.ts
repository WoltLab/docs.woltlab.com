/**
 * Provides the JavaScript code for the person page.
 *
 * @author  Matthias Schmidt
 * @copyright  2001-2021 WoltLab GmbH
 * @license  GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @module  WoltLabSuite/Core/Controller/Person
 */

import FormBuilderDialog from "WoltLabSuite/Core/Form/Builder/Dialog";
import * as Language from "WoltLabSuite/Core/Language";
import * as UiNotification from "WoltLabSuite/Core/Ui/Notification";

let addDialog: FormBuilderDialog;
const editDialogs = new Map<string, FormBuilderDialog>();

interface EditReturnValues {
  formattedInformation: string;
  informationID: number;
}

interface Options {
  canAddInformation: true;
}

/**
 * Opens the edit dialog after clicking on the edit button for a piece of information.
 */
function editInformation(event: Event): void {
  event.preventDefault();

  const currentTarget = event.currentTarget as HTMLElement;
  const information = currentTarget.closest(".jsObjectActionObject") as HTMLElement;
  const informationId = information.dataset.objectId!;

  if (!editDialogs.has(informationId)) {
    editDialogs.set(
      informationId,
      new FormBuilderDialog(
        `personInformationEditDialog${informationId}`,
        "wcf\\data\\person\\information\\PersonInformationAction",
        "getEditDialog",
        {
          actionParameters: {
            informationID: informationId,
          },
          dialog: {
            title: Language.get("wcf.person.information.edit"),
          },
          submitActionName: "submitEditDialog",
          successCallback(returnValues: EditReturnValues) {
            document.getElementById(`personInformation${returnValues.informationID}`)!.innerHTML =
              returnValues.formattedInformation;

            UiNotification.show(Language.get("wcf.person.information.edit.success"));
          },
        },
      ),
    );
  }

  editDialogs.get(informationId)!.open();
}

/**
 * Initializes the JavaScript code for the person page.
 */
export function init(personId: number, options: Options): void {
  if (options.canAddInformation) {
    // Initialize the dialog to add new information.
    addDialog = new FormBuilderDialog(
      "personInformationAddDialog",
      "wcf\\data\\person\\information\\PersonInformationAction",
      "getAddDialog",
      {
        actionParameters: {
          personID: personId,
        },
        dialog: {
          title: Language.get("wcf.person.information.add"),
        },
        submitActionName: "submitAddDialog",
        successCallback() {
          UiNotification.show(Language.get("wcf.person.information.add.success"), () => window.location.reload());
        },
      },
    );

    document.getElementById("personInformationAddButton")!.addEventListener("click", (event) => {
      event.preventDefault();

      addDialog.open();
    });
  }

  document
    .querySelectorAll(".jsEditInformation")
    .forEach((el) => el.addEventListener("click", (ev) => editInformation(ev)));
}
