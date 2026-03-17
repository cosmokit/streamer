import { useEffect, useState } from "react";
import { Video, Clock, Calendar, TrendingUp } from "lucide-react";

interface VideoData {
  id: number;
  title: string;
  url: string;
  duration: string;
  created_at: string;
}

const RecordsPage = () => {
  const [videos, setVideos] = useState<VideoData[]>([]);
  const [totalVideos, setTotalVideos] = useState(0);
  const [totalDuration, setTotalDuration] = useState(0);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/videos', {
      credentials: 'include',
      headers: {
        'Accept': 'application/json',
      }
    })
      .then(res => {
        if (!res.ok) {
          throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }
        return res.json();
      })
      .then(data => {
        if (data.data) {
          setVideos(data.data);
          setTotalVideos(data.summary?.total_videos || 0);
          setTotalDuration(data.summary?.total_duration_minutes || 0);
        }
        setLoading(false);
      })
      .catch(err => {
        console.error('Error loading videos:', err);
        setLoading(false);
      });
  }, []);

  const stats = [
    { icon: Video, color: "hsl(270 75% 55%)", label: "Всего Видео", value: totalVideos.toString() },
    { icon: Clock, color: "hsl(90 85% 50%)", label: "Длительность (мин)", value: totalDuration.toLocaleString() },
    { icon: Calendar, color: "hsl(195 100% 55%)", label: "На Этой Неделе", value: "0" },
    { icon: TrendingUp, color: "hsl(90 85% 50%)", label: "Среднее/день", value: "2.3" },
  ];

  if (loading) {
    return <div className="p-4 md:p-6 lg:p-8 w-full">Загрузка...</div>;
  }

  return (
    <div className="p-4 md:p-6 lg:p-8 w-full h-full flex flex-col overflow-hidden">
      <div className="flex items-center gap-3 mb-4 md:mb-6 shrink-0">
        <Video size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <div>
          <h1 className="text-xl md:text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Видео Записи</h1>
          <p className="text-xs md:text-sm" style={{ color: "hsl(260 15% 50%)" }}>Управление вашей коллекцией YouTube видео</p>
        </div>
      </div>

      <div className="grid grid-cols-2 gap-2 md:grid-cols-4 md:gap-4 mb-4 md:mb-6 shrink-0">
        {stats.map((stat, i) => (
          <div key={i} className="glass-card glass-card-hover rounded-xl p-2.5 md:p-4 flex items-center gap-2 md:gap-3 transition-all">
            <div className="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center shrink-0" style={{
              background: `${stat.color.replace(")", " / 0.15)")}`,
              boxShadow: `0 0 15px ${stat.color.replace(")", " / 0.1)")}`,
            }}>
              <stat.icon size={16} className="md:hidden" style={{ color: stat.color }} />
              <stat.icon size={20} className="hidden md:block" style={{ color: stat.color }} />
            </div>
            <div className="min-w-0">
              <div className="text-[10px] md:text-[11px] truncate" style={{ color: "hsl(260 15% 50%)" }}>{stat.label}</div>
              <div className="text-base md:text-xl font-bold" style={{ color: "hsl(260 20% 90%)" }}>{stat.value}</div>
            </div>
          </div>
        ))}
      </div>

      <h2 className="text-base md:text-lg font-bold mb-3 shrink-0" style={{ color: "hsl(260 20% 90%)" }}>Коллекция Видео</h2>
      <div className="glass-card rounded-xl overflow-hidden flex-1 min-h-0 flex flex-col">
        <div className="overflow-auto flex-1">
          <table className="w-full text-sm">
            <thead className="sticky top-0 z-10">
              <tr style={{ background: "hsl(270 35% 9%)" }}>
                <th className="text-left px-3 md:px-4 py-2.5 font-medium text-xs uppercase tracking-wider" style={{ color: "hsl(260 15% 45%)" }}>Название</th>
                <th className="text-left px-3 md:px-4 py-2.5 font-medium text-xs uppercase tracking-wider" style={{ color: "hsl(260 15% 45%)" }}>Длительность</th>
                <th className="text-left px-3 md:px-4 py-2.5 font-medium text-xs uppercase tracking-wider hidden sm:table-cell" style={{ color: "hsl(260 15% 45%)" }}>Ссылка</th>
                <th className="text-left px-3 md:px-4 py-2.5 font-medium text-xs uppercase tracking-wider hidden sm:table-cell" style={{ color: "hsl(260 15% 45%)" }}>Дата</th>
              </tr>
            </thead>
            <tbody>
              {videos.map((v, i) => (
                <tr key={i} className="border-t transition-colors hover:bg-[hsl(270_35%_16%/0.15)]" style={{
                  borderColor: "hsl(270 25% 16%)",
                  background: i % 2 === 0 ? "hsl(270 35% 7% / 0.5)" : "transparent",
                }}>
                  <td className="px-3 md:px-4 py-2 font-medium text-xs md:text-sm" style={{ color: "hsl(260 20% 85%)" }}>
                    <div className="flex flex-col gap-1">
                      <span>{v.title}</span>
                      <a
                        href={v.url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-sm font-medium hover:underline sm:hidden inline-flex items-center gap-1 py-1"
                        style={{ color: "hsl(var(--accent))" }}
                      >
                        YouTube ↗
                      </a>
                    </div>
                  </td>
                  <td className="px-3 md:px-4 py-2 text-sm md:text-base font-semibold" style={{ color: "hsl(260 20% 80%)" }}>{v.duration}</td>
                  <td className="px-3 md:px-4 py-2 hidden sm:table-cell">
                    <a href={v.url} target="_blank" rel="noopener noreferrer" className="hover:underline text-xs md:text-sm" style={{ color: "hsl(90 85% 55%)" }}>YouTube ↗</a>
                  </td>
                  <td className="px-3 md:px-4 py-2 text-xs md:text-sm hidden sm:table-cell" style={{ color: "hsl(260 15% 50%)" }}>
                    {new Date(v.created_at).toLocaleDateString('ru-RU')}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default RecordsPage;
