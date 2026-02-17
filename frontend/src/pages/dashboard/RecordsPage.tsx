import { Video, Clock, Calendar, TrendingUp } from "lucide-react";

const videos = [
  { title: "DYNASTY WARRIORS ORIGINS", url: "https://youtube.com/watch?v=example1", duration: "18:17:36", date: "10/24/2025" },
  { title: "CLAIR OBSCUR EXPEDITION 33", url: "https://youtube.com/watch?v=example2", duration: "13:45:41", date: "10/24/2025" },
  { title: "KINGDOM COME DELIVERANCE 2", url: "https://youtube.com/watch?v=example3", duration: "1:02:14:27", date: "10/24/2025" },
  { title: "SILENT HILL F", url: "https://youtube.com/watch?v=example4", duration: "08:51:19", date: "10/24/2025" },
  { title: "DELTARUNE CHAPTER 4", url: "https://youtube.com/watch?v=example5", duration: "3:38:28", date: "10/24/2025" },
  { title: "MAFIA THE OLD COUNTRY", url: "https://youtube.com/watch?v=example6", duration: "8:37:06", date: "10/24/2025" },
  { title: "LITTLE NIGHTMARES 3", url: "https://youtube.com/watch?v=example7", duration: "2:48:13", date: "10/24/2025" },
  { title: "METAL GEAR SOLID DELTA SNAKE EATER", url: "https://youtube.com/watch?v=example8", duration: "9:22:15", date: "10/24/2025" },
  { title: "SNIPER ELITE RESISTANCE", url: "https://youtube.com/watch?v=example9", duration: "3:09:21", date: "10/24/2025" },
  { title: "ATOMFALL", url: "https://youtube.com/watch?v=example10", duration: "6:33:45", date: "10/24/2025" },
  { title: "DYING LIGHT THE BEAST", url: "https://youtube.com/watch?v=example11", duration: "8:55:49", date: "10/24/2025" },
  { title: "CIVILIZATION 7", url: "https://youtube.com/watch?v=example12", duration: "10:29:22", date: "10/24/2025" },
  { title: "DOOM THE DARK AGES", url: "https://youtube.com/watch?v=example13", duration: "9:27:18", date: "10/24/2025" },
  { title: "HOLLOW KNIGHT SILKSONG", url: "https://youtube.com/watch?v=example14", duration: "11:44:34", date: "10/24/2025" },
  { title: "AI LIMIT", url: "https://youtube.com/watch?v=example15", duration: "9:13:06", date: "10/24/2025" },
  { title: "LOST SOUL ASIDE", url: "https://youtube.com/watch?v=example16", duration: "11:42:31", date: "10/24/2025" },
  { title: "BLOODBORNE", url: "https://youtube.com/watch?v=example17", duration: "8:05:28", date: "10/24/2025" },
  { title: "ELDEN RING NIGHTREIGN", url: "https://youtube.com/watch?v=example18", duration: "12:13:31", date: "10/24/2025" },
  { title: "RESIDENT EVIL 5", url: "https://youtube.com/watch?v=example19", duration: "8:08:30", date: "10/24/2025" },
  { title: "BORDERLANDS 4", url: "https://youtube.com/watch?v=example20", duration: "13:02:41", date: "10/24/2025" },
];

const totalDuration = 37870;

const stats = [
  { icon: Video, color: "hsl(270 75% 55%)", label: "Всего Видео", value: "70" },
  { icon: Clock, color: "hsl(90 85% 50%)", label: "Длительность (мин)", value: totalDuration.toLocaleString() },
  { icon: Calendar, color: "hsl(195 100% 55%)", label: "На Этой Неделе", value: "0" },
  { icon: TrendingUp, color: "hsl(90 85% 50%)", label: "Среднее/день", value: "2.3" },
];

const RecordsPage = () => {
  return (
    <div className="p-4 md:p-8 max-w-5xl">
      <div className="flex items-center gap-3 mb-6">
        <Video size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <div>
          <h1 className="text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Видео Записи</h1>
          <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Управление вашей коллекцией YouTube видео</p>
        </div>
      </div>

      <div className="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4 mb-6 md:mb-8">
        {stats.map((stat, i) => (
          <div key={i} className="glass-card glass-card-hover rounded-xl p-4 flex items-center gap-3 transition-all">
            <div className="w-10 h-10 rounded-lg flex items-center justify-center" style={{
              background: `${stat.color.replace(")", " / 0.15)")}`,
              boxShadow: `0 0 15px ${stat.color.replace(")", " / 0.1)")}`,
            }}>
              <stat.icon size={20} style={{ color: stat.color }} />
            </div>
            <div>
              <div className="text-[11px]" style={{ color: "hsl(260 15% 50%)" }}>{stat.label}</div>
              <div className="text-xl font-bold" style={{ color: "hsl(260 20% 90%)" }}>{stat.value}</div>
            </div>
          </div>
        ))}
      </div>

      <h2 className="text-lg font-bold mb-4" style={{ color: "hsl(260 20% 90%)" }}>Коллекция Видео</h2>
      <div className="glass-card rounded-xl overflow-x-auto">
        <table className="w-full text-sm min-w-[500px]">
          <thead>
            <tr style={{ background: "hsl(270 35% 9% / 0.8)" }}>
              <th className="text-left px-3 md:px-4 py-3 font-medium text-xs uppercase tracking-wider" style={{ color: "hsl(260 15% 45%)" }}>Название</th>
              <th className="text-left px-3 md:px-4 py-3 font-medium text-xs uppercase tracking-wider hidden sm:table-cell" style={{ color: "hsl(260 15% 45%)" }}>Ссылка</th>
              <th className="text-left px-3 md:px-4 py-3 font-medium text-xs uppercase tracking-wider" style={{ color: "hsl(260 15% 45%)" }}>Длительность</th>
              <th className="text-left px-3 md:px-4 py-3 font-medium text-xs uppercase tracking-wider hidden sm:table-cell" style={{ color: "hsl(260 15% 45%)" }}>Дата</th>
            </tr>
          </thead>
          <tbody>
            {videos.map((v, i) => (
              <tr key={i} className="border-t transition-colors hover:bg-[hsl(270_35%_16%/0.15)]" style={{
                borderColor: "hsl(270 25% 16%)",
                background: i % 2 === 0 ? "hsl(270 35% 7% / 0.5)" : "transparent",
              }}>
                <td className="px-3 md:px-4 py-2.5 font-medium text-xs md:text-sm" style={{ color: "hsl(260 20% 85%)" }}>{v.title}</td>
                <td className="px-3 md:px-4 py-2.5 hidden sm:table-cell">
                  <a href={v.url} target="_blank" rel="noopener noreferrer" className="hover:underline text-xs md:text-sm" style={{ color: "hsl(90 85% 55%)" }}>YouTube ↗</a>
                </td>
                <td className="px-3 md:px-4 py-2.5 text-xs md:text-sm" style={{ color: "hsl(260 15% 50%)" }}>{v.duration}</td>
                <td className="px-3 md:px-4 py-2.5 text-xs md:text-sm hidden sm:table-cell" style={{ color: "hsl(260 15% 50%)" }}>{v.date}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default RecordsPage;
