/**
 * Provides the JavaScript code for the person page.
 *
 * @author  Matthias Schmidt
 * @copyright  2001-2021 WoltLab GmbH
 * @license  GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @module  WoltLabSuite/Core/Controller/Person
 */
define(["require", "exports", "tslib", "WoltLabSuite/Core/Form/Builder/Dialog", "WoltLabSuite/Core/Language", "WoltLabSuite/Core/Ui/Notification"], function (require, exports, tslib_1, Dialog_1, Language, UiNotification) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.init = void 0;
    Dialog_1 = tslib_1.__importDefault(Dialog_1);
    Language = tslib_1.__importStar(Language);
    UiNotification = tslib_1.__importStar(UiNotification);
    let addDialog;
    const editDialogs = new Map();
    /**
     * Opens the edit dialog after clicking on the edit button for a piece of information.
     */
    function editInformation(event) {
        event.preventDefault();
        const currentTarget = event.currentTarget;
        const information = currentTarget.closest(".jsObjectActionObject");
        const informationId = information.dataset.objectId;
        if (!editDialogs.has(informationId)) {
            editDialogs.set(informationId, new Dialog_1.default(`personInformationEditDialog${informationId}`, "wcf\\data\\person\\information\\PersonInformationAction", "getEditDialog", {
                actionParameters: {
                    informationID: informationId,
                },
                dialog: {
                    title: Language.get("wcf.person.information.edit"),
                },
                submitActionName: "submitEditDialog",
                successCallback(returnValues) {
                    document.getElementById(`personInformation${returnValues.informationID}`).innerHTML =
                        returnValues.formattedInformation;
                    UiNotification.show(Language.get("wcf.person.information.edit.success"));
                },
            }));
        }
        editDialogs.get(informationId).open();
    }
    /**
     * Initializes the JavaScript code for the person page.
     */
    function init(personId, options) {
        if (options.canAddInformation) {
            // Initialize the dialog to add new information.
            addDialog = new Dialog_1.default("personInformationAddDialog", "wcf\\data\\person\\information\\PersonInformationAction", "getAddDialog", {
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
            });
            document.getElementById("personInformationAddButton").addEventListener("click", (event) => {
                event.preventDefault();
                addDialog.open();
            });
        }
        document
            .querySelectorAll(".jsEditInformation")
            .forEach((el) => el.addEventListener("click", (ev) => editInformation(ev)));
    }
    exports.init = init;
});
