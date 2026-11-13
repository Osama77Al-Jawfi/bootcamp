
"use strict";



const userInput = document.getElementById("userInput");

const unsafeOutput = document.getElementById("unsafeOutput");

const safeOutput = document.getElementById("safeOutput");

const unsafeBtn = document.getElementById("unsafeBtn");

const safeBtn = document.getElementById("safeBtn");

const clearBtn = document.getElementById("clearBtn");

const statusMessage = document.getElementById("statusMessage");



if (
    !userInput ||
    !unsafeOutput ||
    !safeOutput ||
    !unsafeBtn ||
    !safeBtn ||
    !clearBtn ||
    !statusMessage
) {
    console.error("Error: Some HTML elements were not found!");

} else {

    console.log("DOM XSS Security Tool Started Successfully!");

}



function displayUnsafe() {

    const inputValue = userInput.value;

    if (inputValue.trim() === "") {
        statusMessage.textContent = "Please enter some input.";
        return;
    }

    console.log("[UNSAFE] User Input:", inputValue);

    unsafeOutput.innerHTML = inputValue;

    statusMessage.textContent =
        "Input displayed using innerHTML (unsafe method).";

    console.log("[UNSAFE] Input inserted using innerHTML");

}


function displaySafe() {

    const inputValue = userInput.value;

    if (inputValue.trim() === "") {
        statusMessage.textContent = "Please enter some input.";
        return;
    }

    console.log("[SAFE] User Input:", inputValue);

    safeOutput.textContent = inputValue;

    statusMessage.textContent =
        "Input displayed using textContent (safe method).";

    console.log("[SAFE] Input inserted using textContent");

}



function clearOutputs() {

    unsafeOutput.replaceChildren();

    safeOutput.replaceChildren();

    statusMessage.textContent = "Outputs cleared.";

    console.log("Outputs cleared successfully!");

}

unsafeBtn.addEventListener("click", displayUnsafe);

safeBtn.addEventListener("click", displaySafe);

clearBtn.addEventListener("click", clearOutputs);
