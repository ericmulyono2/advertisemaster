// Collect 50 classic TV commercials + 50 royalty-free stock clips from Internet Archive.
const MAX = 12 * 1024 * 1024; // skip files > 12MB
async function search(q, rows) {
  const u = `https://archive.org/advancedsearch.php?q=${encodeURIComponent(q)}&fl[]=identifier&rows=${rows}&output=json`;
  const r = await fetch(u); const j = await r.json();
  return j.response.docs.map(d => d.identifier);
}
async function pickMp4(id) {
  try {
    const r = await fetch(`https://archive.org/metadata/${id}`);
    const j = await r.json();
    if (!j.files) return null;
    let mp4 = j.files.filter(f => f.name.toLowerCase().endsWith('.mp4') && +f.size > 50000);
    if (!mp4.length) return null;
    mp4.sort((a,b)=>(+a.size)-(+b.size)); // smallest first
    const pick = mp4.find(f => +f.size <= MAX) || mp4[0];
    if (+pick.size > MAX) return null;
    const title = (j.metadata && (Array.isArray(j.metadata.title)?j.metadata.title[0]:j.metadata.title)) || id;
    return { url: `https://archive.org/download/${id}/${encodeURIComponent(pick.name)}`, size: +pick.size, title: String(title).slice(0,150) };
  } catch { return null; }
}
async function gather(q, want, kind) {
  const ids = await search(q, want * 4);
  const out = [];
  for (const id of ids) {
    if (out.length >= want) break;
    const m = await pickMp4(id);
    if (m) { out.push({ ...m, kind }); process.stderr.write('.'); }
  }
  process.stderr.write(`\n${kind}: ${out.length}\n`);
  return out;
}
const ads = await gather('collection:classic_tv_commercials AND mediatype:movies', 50, 'ad');
const stock = await gather('(pexels OR pixabay) AND mediatype:movies AND format:(MPEG4)', 50, 'stock');
const all = [...ads, ...stock];
console.log(JSON.stringify(all, null, 0));
