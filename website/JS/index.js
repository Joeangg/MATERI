function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function lampuLaluLintas() {
    while (true) {
        console.log("🔴 MERAH - Berhenti");
        await delay(5000);

        console.log("🟡 KUNING - Hati-hati");
        await delay(1000);

        console.log("🟢 HIJAU - Berhenti");
        await delay(5000);
    }
}

lampuLaluLintas();