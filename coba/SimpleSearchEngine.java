import java.util.*;

/**
 * SimpleSearchEngine
 * ------------------
 * Demonstrasi sederhana cara kerja mesin pencari:
 * 1. Indexing  -> membangun inverted index dari kumpulan dokumen
 * 2. Ranking   -> menghitung skor relevansi (TF - Term Frequency) tiap dokumen terhadap query
 * 3. Serving   -> mengembalikan hasil yang diurutkan dari paling relevan
 *
 * Catatan: ini versi edukasi, bukan mesin pencari produksi.
 * Mesin pencari nyata (Google, Brave, dll) menambahkan crawling otomatis,
 * PageRank, machine learning, dan miliaran dokumen.
 */
public class SimpleSearchEngine {

    // "Database" dokumen sederhana: id -> isi teks
    private final Map<Integer, String> documents = new LinkedHashMap<>();

    // Inverted index: kata -> (id dokumen -> jumlah kemunculan kata)
    private final Map<String, Map<Integer, Integer>> invertedIndex = new HashMap<>();

    /** Tahap 1: CRAWLING (disederhanakan menjadi menambah dokumen manual) */
    public void addDocument(int id, String content) {
        documents.put(id, content);
        indexDocument(id, content);
    }

    /** Tahap 2: INDEXING - memecah teks menjadi kata dan mencatat frekuensinya */
    private void indexDocument(int id, String content) {
        String[] words = content.toLowerCase().split("\\W+");
        for (String word : words) {
            if (word.isBlank()) continue;
            invertedIndex
                .computeIfAbsent(word, k -> new HashMap<>())
                .merge(id, 1, Integer::sum);
        }
    }

    /** Tahap 3: RANKING & SERVING - mencari dan mengurutkan dokumen berdasarkan relevansi */
    public List<SearchResult> search(String query) {
        String[] queryWords = query.toLowerCase().split("\\W+");
        Map<Integer, Integer> scores = new HashMap<>();

        for (String word : queryWords) {
            Map<Integer, Integer> docsContainingWord = invertedIndex.get(word);
            if (docsContainingWord == null) continue;

            for (Map.Entry<Integer, Integer> entry : docsContainingWord.entrySet()) {
                scores.merge(entry.getKey(), entry.getValue(), Integer::sum);
            }
        }

        List<SearchResult> results = new ArrayList<>();
        for (Map.Entry<Integer, Integer> entry : scores.entrySet()) {
            results.add(new SearchResult(entry.getKey(), documents.get(entry.getKey()), entry.getValue()));
        }

        // Urutkan dari skor tertinggi ke terendah (hasil paling relevan di atas)
        results.sort((a, b) -> Integer.compare(b.score, a.score));
        return results;
    }

    /** Struktur sederhana untuk menyimpan satu hasil pencarian */
    static class SearchResult {
        int id;
        String content;
        int score;

        SearchResult(int id, String content, int score) {
            this.id = id;
            this.content = content;
            this.score = score;
        }

        @Override
        public String toString() {
            return String.format("[skor=%d] (doc #%d) %s", score, id, content);
        }
    }

    public static void main(String[] args) {
        SimpleSearchEngine engine = new SimpleSearchEngine();

        // Simulasi "crawling" beberapa halaman web
        engine.addDocument(1, "Restoran Yunani terbaik di San Francisco menyajikan gyros dan hummus");
        engine.addDocument(2, "Panduan lengkap belajar bahasa pemrograman Java untuk pemula");
        engine.addDocument(3, "Cara kerja mesin pencari: crawling, indexing, dan ranking");
        engine.addDocument(4, "Restoran Italia di San Francisco menawarkan pizza dan pasta autentik");
        engine.addDocument(5, "Belajar Java: dasar-dasar OOP, class, dan object");

        // Simulasi query dari pengguna
        String query = "restoran San Francisco";
        System.out.println("Query: \"" + query + "\"\n");

        List<SearchResult> hasil = engine.search(query);

        if (hasil.isEmpty()) {
            System.out.println("Tidak ada hasil ditemukan.");
        } else {
            System.out.println("Hasil pencarian (diurutkan berdasarkan relevansi):");
            for (SearchResult r : hasil) {
                System.out.println(r);
            }
        }
    }
}