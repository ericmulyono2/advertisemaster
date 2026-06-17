// Resolve official YouTube trailer IDs for ~130 films (2020-2026) and validate
// each is public + embeddable via YouTube oEmbed (no API key). Outputs up to 100.
const TITLES = [
  "Tenet","Soul 2020 Pixar","Wonder Woman 1984","Mulan 2020","The Invisible Man 2020","Birds of Prey",
  "Onward","The Old Guard","Enola Holmes","Sonic the Hedgehog","Bad Boys for Life","News of the World",
  "Dune 2021","No Time to Die","Spider-Man No Way Home","Shang-Chi","Black Widow","Eternals","Free Guy",
  "A Quiet Place Part II","Cruella","Encanto","The Matrix Resurrections","Ghostbusters Afterlife",
  "Venom Let There Be Carnage","F9 Fast and Furious","The Suicide Squad 2021","Luca Pixar","Raya and the Last Dragon",
  "Top Gun Maverick","The Batman 2022","Avatar The Way of Water","Black Panther Wakanda Forever",
  "Doctor Strange in the Multiverse of Madness","Thor Love and Thunder","Jurassic World Dominion","Nope",
  "Everything Everywhere All at Once","The Northman","Elvis 2022","Minions The Rise of Gru","Lightyear",
  "Sonic the Hedgehog 2","Black Adam","Puss in Boots The Last Wish",
  "Oppenheimer","Barbie 2023","Guardians of the Galaxy Vol 3","Spider-Man Across the Spider-Verse",
  "John Wick Chapter 4","Mission Impossible Dead Reckoning","The Super Mario Bros Movie","Wonka",
  "Killers of the Flower Moon","The Flash 2023","Indiana Jones and the Dial of Destiny",
  "Aquaman and the Lost Kingdom","Napoleon 2023","The Marvels","Ant-Man and the Wasp Quantumania","Elemental",
  "Creed III","Transformers Rise of the Beasts","The Hunger Games Ballad of Songbirds and Snakes",
  "Dune Part Two","Deadpool and Wolverine","Inside Out 2","Wicked 2024","Gladiator II","Furiosa A Mad Max Saga",
  "Godzilla x Kong The New Empire","Kung Fu Panda 4","Kingdom of the Planet of the Apes","A Quiet Place Day One",
  "Moana 2","Mufasa The Lion King","Joker Folie a Deux","Beetlejuice Beetlejuice","Alien Romulus","Twisters 2024",
  "Bad Boys Ride or Die","Venom The Last Dance","Sonic the Hedgehog 3","Despicable Me 4",
  "Captain America Brave New World","Thunderbolts Marvel","Mission Impossible The Final Reckoning",
  "Jurassic World Rebirth","Superman 2025 James Gunn","The Fantastic Four First Steps","Avatar Fire and Ash",
  "Zootopia 2","Tron Ares","How to Train Your Dragon 2025","Lilo and Stitch 2025","F1 The Movie Brad Pitt",
  "Snow White 2025","A Minecraft Movie","Wicked For Good","Mickey 17","Final Destination Bloodlines",
  "28 Years Later","The Conjuring Last Rites","Now You See Me Now You Dont",
  "Avengers Doomsday","The Batman Part II","Toy Story 5","Shrek 5","Spider-Man Beyond the Spider-Verse",
  "Dune Messiah movie","The Mandalorian and Grogu","Ice Age 6","Five Nights at Freddys 2","Frozen 3",
  "Wuthering Heights 2026","Moana live action","Supergirl 2026","Street Fighter movie 2026",
  "Elden Ring movie","Masters of the Universe 2026","Practical Magic 2"
];

const WANT = 100;
const HDRS = { "Accept-Language": "en-US,en", "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)", "Cookie": "CONSENT=YES+cb" };
const sleep = ms => new Promise(r => setTimeout(r, ms));

async function firstVideoId(title) {
  const url = "https://www.youtube.com/results?search_query=" + encodeURIComponent(title + " official trailer");
  for (let attempt = 0; attempt < 3; attempt++) {
    try {
      const r = await fetch(url, { headers: HDRS });
      const html = await r.text();
      const m = html.match(/"videoId":"([A-Za-z0-9_-]{11})"/);
      if (m) return m[1];
    } catch {}
    await sleep(5000 * (attempt + 1)); // backoff on block/empty
  }
  return null;
}
async function validate(vid) {
  for (let attempt = 0; attempt < 2; attempt++) {
    try {
      const r = await fetch(`https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=${vid}&format=json`);
      if (r.ok) { const j = await r.json(); return { title: j.title, author: j.author_name }; }
      if (r.status === 401 || r.status === 404) return null; // not embeddable/public
    } catch {}
    await sleep(2500);
  }
  return null;
}

const out = [], seen = new Set();
for (const t of TITLES) {
  if (out.length >= WANT) break;
  const vid = await firstVideoId(t);
  if (!vid || seen.has(vid)) { process.stderr.write("x"); await sleep(1500); continue; }
  const v = await validate(vid);
  if (!v) { process.stderr.write("-"); await sleep(1500); continue; }
  seen.add(vid);
  out.push({ movie: t, videoId: vid, title: v.title, author: v.author });
  process.stderr.write(out.length % 10 === 0 ? String(out.length) : ".");
  await sleep(2500);
}
process.stderr.write(`\nresolved: ${out.length}\n`);
console.log(JSON.stringify(out, null, 0));
