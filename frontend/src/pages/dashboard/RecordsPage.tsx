import { useEffect, useState } from "react";
import { Video, Clock } from "lucide-react";

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
  ];

  if (loading) {
    return <div className="p-4 md:p-8 max-w-5xl">Загрузка...</div>;
  }
  return (
    <div className="p-4 md:p-8 max-w-5xl">
      <div className="flex items-center gap-3 mb-6">
        <Video size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <div>
          <h1 className="text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Видео Записи</h1>
          <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Управление вашей коллекцией YouTube видео</p>
        </div>
      </div>

      <div className="grid grid-cols-2 gap-3 md:gap-4 mb-6 md:mb-8">
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
                <td className="px-3 md:px-4 py-2.5 text-xs md:text-sm hidden sm:table-cell" style={{ color: "hsl(260 15% 50%)" }}>
                  {new Date(v.created_at).toLocaleDateString('ru-RU')}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default RecordsPage;
