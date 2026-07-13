/**
 * Layout struk thermal 80mm — selaras dengan f_jualcetaknota.php (lebar 47 karakter, condensed).
 */
const WIDTH = 47;
const DEF = 1;

function spasiStr(str, pjg) {
  str = String(str != null ? str : "");
  if (str.length >= pjg) return str.substring(0, pjg);
  return str + " ".repeat(pjg - str.length);
}

function spasiNum(str, pjg) {
  str = String(str != null ? str : "");
  if (str.length >= pjg) return str.substring(0, pjg);
  return " ".repeat(pjg - str.length) + str;
}

function spasiCenter(str, width) {
  str = String(str != null ? str : "");
  if (str.length >= width) return str.substring(0, width);
  const left = Math.floor((width - str.length) / 2);
  return " ".repeat(left) + str + " ".repeat(width - str.length - left);
}

function formatNumber(n) {
  const num = Math.round(Number(n) || 0);
  const neg = num < 0;
  const s = String(Math.abs(num));
  let out = "";
  let j = 0;
  for (let i = s.length; i > 0; i--) {
    j++;
    const ch = s.charAt(i - 1);
    if (j !== 1 && j % 3 === 1) out = "." + out;
    out = ch + out;
  }
  return neg ? "-" + out : out;
}

function gantitgl(tgl) {
  if (!tgl) return "";
  const parts = String(tgl).split("-");
  if (parts.length !== 3) return String(tgl);
  return parts[2] + "-" + parts[1] + "-" + parts[0];
}

function parseAmount(val) {
  if (typeof val === "number" && !isNaN(val)) return val;
  if (!val) return 0;
  const s = String(val).replace(/[^\d.-]/g, "");
  return Number(s) || 0;
}

function buildNotaText(data) {
  // ESC/POS: init, buka laci, condensed on (sama seperti f_jualcetaknota.php)
  const escInit = Buffer.from([0x1b, 0x40]);
  const openDrawer = Buffer.from([0x1b, 0x70, 0x30, 0x19, 0xfa]);
  const condensedOn = Buffer.from([0x0f]);
  const condensedOff = Buffer.from([0x12]);
  const cutPaper = Buffer.from([0x1d, 0x56, 0x30, 0x00]);

  const lines = [];
  const p = function () {
    return spasiStr("", DEF);
  };

  const nmToko = data.nm_toko || "TOKOFAFA";
  const alToko = data.al_toko || "";
  const tglJual =
    data.tgl_jual || (data.tgltime ? String(data.tgltime).split(" ")[0] : "");

  lines.push(p() + spasiCenter(nmToko, WIDTH));
  if (alToko) {
    lines.push(spasiCenter(alToko, WIDTH + DEF));
  }
  lines.push("");
  lines.push(p() + "No.Struk " + spasiStr("", 5) + ":" + spasiStr(data.no_fakjual || "", 20));
  lines.push(p() + "Tanggal  " + spasiStr("", 5) + ":" + spasiStr(gantitgl(tglJual), 10));
  if (data.nm_pel) {
    lines.push(p() + "Pembeli  " + spasiStr("", 5) + ":" + spasiStr(data.nm_pel, 30));
  }

  if (data.nm_member) {
    lines.push(p() + "Member   " + spasiStr("", 5) + ":" + spasiStr(data.nm_member, 30));
    const poinEarned = parseAmount(data.poin_earned);
    if (poinEarned > 0) {
      lines.push(
        p() + "Poin Dapat" + spasiStr("", 3) + ":" +
        spasiStr(formatNumber(poinEarned) + " Poin", 30)
      );
    }
    const poinSaldo = parseAmount(data.poin_saldo);
    lines.push(
      p() + "Poin Saldo" + spasiStr("", 3) + ":" +
      spasiStr(formatNumber(poinSaldo) + " Poin", 30)
    );
  }

  lines.push(p() + "-----------------------------------------------");
  lines.push(p() + "No." + spasiStr("", 5) + "Nama Barang");
  lines.push(
    p() + spasiStr("", 5) +
    spasiStr("Jml", 10) + spasiStr("Disc%", 12) + spasiStr("Harga", 11) + spasiStr("SubTotal", 12)
  );
  lines.push(p() + "-----------------------------------------------");

  let itemTotal = 0;
  (data.items || []).forEach(function (item, idx) {
    const qty = parseAmount(item.qty);
    const hrg = parseAmount(item.hrg);
    const discPct = parseAmount(item.discpct != null ? item.discpct : item.disc);
    const subtot = parseAmount(item.subtot);
    itemTotal += subtot;

    lines.push(p() + spasiNum(idx + 1, 3) + "." + spasiStr("", 1) + (item.nmbrg || ""));
    lines.push(
      spasiStr("", 4 + DEF) +
      spasiNum(qty, 3) +
      spasiStr(item.sat || "", 5) +
      spasiNum(discPct, 9) +
      spasiStr("", 2) +
      spasiNum(formatNumber(hrg), 9) +
      spasiStr("", 2) +
      spasiNum(formatNumber(subtot), 12)
    );
  });

  const belanja = parseAmount(data.belanja) || itemTotal;
  const disctot = parseAmount(data.disctot_raw != null ? data.disctot_raw : data.disctot);
  const voucher = parseAmount(data.voucher_raw != null ? data.voucher_raw : data.voucher);
  const ongkir = parseAmount(data.ongkir_raw != null ? data.ongkir_raw : data.ongkir);
  const bayar = parseAmount(data.bayar);
  const susuk = parseAmount(data.susuk);
  const saldohut = parseAmount(data.saldohut);

  lines.push(p() + "-----------------------------------------------");
  lines.push(
    spasiStr("", 7 + DEF) + spasiNum("Total", 15) + spasiStr("", 4) +
    "Rp. " + spasiNum(formatNumber(belanja), 16)
  );

  if (disctot > 0) {
    lines.push(
      spasiStr("", 7 + DEF) + spasiNum("Disc Nota", 15) + spasiStr("", 4) +
      "Rp. " + spasiNum(formatNumber(disctot), 16)
    );
  }
  if (voucher > 0) {
    lines.push(
      spasiStr("", 7 + DEF) + spasiNum("Voucher", 15) + spasiStr("", 4) +
      "Rp. " + spasiNum(formatNumber(voucher), 16)
    );
  }
  if (ongkir > 0) {
    lines.push(
      spasiStr("", 7 + DEF) + spasiNum("Ongkir", 15) + spasiStr("", 4) +
      "Rp. " + spasiNum(formatNumber(ongkir), 16)
    );
  }

  const kdBayar = String(data.kd_bayar || "TUNAI").toUpperCase();
  if (kdBayar === "TUNAI") {
    lines.push(
      spasiStr("", 7 + DEF) + spasiNum("Uang Tunai", 15) + spasiStr("", 4) +
      "Rp. " + spasiNum(formatNumber(bayar), 16)
    );
    lines.push(
      spasiStr("", 7 + DEF) + spasiNum("Kembali", 15) + spasiStr("", 4) +
      "Rp. " + spasiNum(formatNumber(susuk), 16)
    );
  } else {
    lines.push(
      spasiStr("", 7 + DEF) + spasiNum("Uang Tunai", 15) + spasiStr("", 4) +
      "Rp. " + spasiNum(formatNumber(bayar), 16)
    );
    lines.push(
      spasiStr("", 7 + DEF) + spasiNum("Kekurangan", 15) + spasiStr("", 4) +
      "Rp. " + spasiNum(formatNumber(saldohut), 16)
    );
    lines.push(
      spasiStr("", 7 + DEF) + spasiNum("Jatuh Tempo", 15) + spasiStr("", 4) +
      spasiNum(String(data.jtempo || ""), 16)
    );
  }

  lines.push("");
  lines.push(spasiCenter("Kosmetik, Hijab, Aksesoris", WIDTH + DEF));
  lines.push(spasiCenter("BARANG YG.SUDAH DIBELI TDK BISA DIKEMBALIKAN", 46 + DEF));
  lines.push(spasiCenter("*TERIMA KASIH*", WIDTH + DEF));
  lines.push("");
  lines.push("");
  lines.push("");
  lines.push("");
  lines.push("");

  const textBody = lines.join("\n") + "\n\n\n\n\n\n";
  return Buffer.concat([
    escInit,
    openDrawer,
    condensedOn,
    Buffer.from(textBody, "binary"),
    condensedOff,
    cutPaper,
  ]);
}

module.exports = { buildNotaText, formatNumber, spasiCenter };
