import { useState } from "react";
import { Play, Globe, Bell, Zap } from "lucide-react";

const notifications = [
  "Трансляция успешно запущена (День 3)",
  "Трансляция успешно запущена (День 2)",
  "Трансляция успешно запущена (День 1)",
];

const TrafficPage = () => {
  const [twitchLink, setTwitchLink] = useState("https://www.twitch.tv/sawenshawkat");
  const [xLink, setXLink] = useState("");
  const currentDay = 3;
  const totalDays = 4;

  return (
    <div className="p-4 md:p-8 max-w-5xl">
      <div className="flex items-center gap-3 mb-6">
        <Zap size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <h1 className="text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Панель управления</h1>
      </div>

      {/* Start Stream */}
      <div className="glass-card rounded-xl p-6 mb-6">
        <div className="flex items-center gap-3 mb-3">
          <div className="w-10 h-10 rounded-lg flex items-center justify-center glow-btn">
            <Play size={20} color="#fff" />
          </div>
          <h2 className="text-xl font-bold" style={{ color: "hsl(260 20% 90%)" }}>Запустить Стрим</h2>
        </div>
        <p className="text-sm mb-4" style={{ color: "hsl(260 15% 50%)" }}>
          До компаньона запускайте трансляцию на 2 часа, после получения статуса компаньона на 8-10 часов после 20-00 по мск
        </p>
        <label className="text-sm mb-1 block" style={{ color: "hsl(260 15% 55%)" }}>Ссылка на Twitch канал</label>
        <input
          value={twitchLink}
          onChange={(e) => setTwitchLink(e.target.value)}
          className="w-full px-4 py-2.5 rounded-lg text-sm mb-4 outline-none transition-all focus:ring-1"
          style={{
            background: "hsl(270 35% 7%)",
            color: "hsl(260 20% 90%)",
            border: "1px solid hsl(270 25% 20%)",
          }}
        />
        <button className="w-full py-3 rounded-lg font-semibold text-white glow-btn">
          Запустить Стрим
        </button>
      </div>

      {/* Day counter */}
      <div className="glass-card rounded-xl p-6 mb-6 text-center relative overflow-hidden">
        <div className="absolute inset-0 pointer-events-none" style={{
          background: "radial-gradient(ellipse at center, hsl(90 85% 45% / 0.05), transparent 70%)"
        }} />
        <h2 className="text-3xl font-bold mb-1 glow-text relative z-10" style={{ color: "hsl(260 20% 93%)" }}>
          День {currentDay} из {totalDays}
        </h2>
        <p className="text-sm mb-4 relative z-10" style={{ color: "hsl(260 15% 50%)" }}>Продолжайте стримить каждый день</p>
        <div className="w-full h-2.5 rounded-full relative z-10" style={{ background: "hsl(270 25% 10%)" }}>
          <div className="h-full rounded-full transition-all" style={{
            width: `${(currentDay / totalDays) * 100}%`,
            background: "linear-gradient(90deg, hsl(270 75% 50%), hsl(90 85% 45%))",
            boxShadow: "0 0 15px hsl(90 85% 45% / 0.4)",
          }} />
        </div>
      </div>

      {/* Social + Notifications */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="glass-card glass-card-hover rounded-xl p-6 transition-all">
          <div className="flex items-center gap-3 mb-4">
            <Globe size={22} style={{ color: "hsl(195 100% 60%)" }} />
            <h2 className="text-lg font-bold" style={{ color: "hsl(260 20% 90%)" }}>Социальные Сети</h2>
          </div>
          <p className="text-sm mb-3" style={{ color: "hsl(260 15% 50%)" }}>Добавьте ссылку на профиль X</p>
          <div className="flex gap-2">
            <input
              value={xLink}
              onChange={(e) => setXLink(e.target.value)}
              placeholder="https://x.com/yourusername"
              className="flex-1 px-4 py-2 rounded-lg text-sm outline-none"
              style={{ background: "hsl(270 35% 7%)", color: "hsl(260 20% 90%)", border: "1px solid hsl(270 25% 20%)" }}
            />
            <button className="px-4 py-2 rounded-lg text-sm font-medium transition-all" style={{
              background: "hsl(270 25% 14%)",
              color: "hsl(260 15% 75%)",
              border: "1px solid hsl(270 25% 22%)"
            }}>Добавить</button>
          </div>
        </div>

        <div className="glass-card glass-card-hover rounded-xl p-6 transition-all">
          <div className="flex items-center gap-3 mb-4">
            <Bell size={22} style={{ color: "hsl(90 85% 55%)" }} />
            <h2 className="text-lg font-bold" style={{ color: "hsl(260 20% 90%)" }}>Уведомления</h2>
          </div>
          <div className="flex flex-col gap-2">
            {notifications.map((n, i) => (
              <div key={i} className="px-4 py-2.5 rounded-lg text-sm transition-all" style={{
                background: "hsl(270 35% 7%)",
                color: "hsl(260 15% 55%)",
                border: i === 0 ? "1px solid hsl(90 85% 45% / 0.3)" : "1px solid hsl(270 25% 18%)",
                boxShadow: i === 0 ? "0 0 10px hsl(90 85% 45% / 0.05)" : "none",
              }}>
                {n}
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default TrafficPage;
