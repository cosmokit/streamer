import { useState, useEffect } from "react";
import { Play, Globe, Bell, Zap, AlertCircle } from "lucide-react";
import { toast } from "@/hooks/use-toast";

interface Notification {
  id: number;
  message: string;
  created_at: string;
}

const TrafficPage = () => {
  const [twitchLink, setTwitchLink] = useState("");
  const [xLink, setXLink] = useState("");
  const [currentDay, setCurrentDay] = useState(0);
  const [loading, setLoading] = useState(false);
  const [starting, setStarting] = useState(false);
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [showSupportMessage, setShowSupportMessage] = useState(false);
  const totalDays = 4;

  useEffect(() => {
    loadStreamData();
    loadNotifications();
  }, []);

  const loadStreamData = () => {
    fetch('/api/stream-runs', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(res => res.json())
      .then(data => {
        setCurrentDay(data.current_day || 0);
        if (data.twitch_url) {
          setTwitchLink(data.twitch_url);
        }
      })
      .catch(err => console.error('Error loading stream data:', err));
  };

  const loadNotifications = () => {
    fetch('/api/notifications', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(res => res.json())
      .then(data => {
        setNotifications(data.data || []);
      })
      .catch(err => console.error('Error loading notifications:', err));
  };

  const handleStartStream = () => {
    if (!twitchLink) {
      toast({ title: "Ошибка", description: "Укажите ссылку на Twitch канал", variant: "destructive" });
      return;
    }

    setStarting(true);
    setShowSupportMessage(false);

    fetch('/api/stream-runs/start', {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ twitch_url: twitchLink })
    })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        toast({ title: "Успешно", description: data.message });
        setStarting(false);
        loadStreamData();
        loadNotifications();
      })
      .catch(err => {
        console.error('Error starting stream:', err);
        
        if (err.error_type === 'no_api_key' || err.error_type === 'nezhna_error' || err.error_type === 'connection_error' || err.error_type === 'no_twitch') {
          setShowSupportMessage(true);
          toast({ 
            title: "Требуется помощь", 
            description: err.message || "Обратитесь в поддержку",
            variant: "default"
          });
        } else {
          toast({ 
            title: "Ошибка", 
            description: err.message || "Не удалось запустить стрим", 
            variant: "destructive" 
          });
        }
        setStarting(false);
      });
  };

  const handleSaveSocial = () => {
    if (!xLink) {
      toast({ title: "Ошибка", description: "Укажите ссылку на X", variant: "destructive" });
      return;
    }

    setLoading(true);
    fetch('/api/social-links', {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ platform: 'x', url: xLink })
    })
      .then(res => res.json())
      .then(data => {
        toast({ title: "Успешно", description: "Ссылка сохранена" });
        setLoading(false);
      })
      .catch(err => {
        console.error('Error saving social:', err);
        toast({ title: "Ошибка", description: "Не удалось сохранить", variant: "destructive" });
        setLoading(false);
      });
  };

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
        
        {showSupportMessage && (
          <div className="mb-4 p-4 rounded-lg flex items-start gap-3" style={{ background: "hsl(45 60% 45% / 0.08)", border: "1px solid hsl(45 60% 50% / 0.2)" }}>
            <AlertCircle size={20} style={{ color: "hsl(45 90% 65%)", flexShrink: 0, marginTop: "2px" }} />
            <div>
              <p className="text-sm font-medium mb-1" style={{ color: "hsl(45 90% 65%)" }}>
                Требуется помощь
              </p>
              <p className="text-xs" style={{ color: "hsl(45 90% 55%)" }}>
                Сервис временно недоступен. Пожалуйста, обратитесь в техническую поддержку для решения проблемы.
              </p>
              <a 
                href="https://t.me/profitstream_support" 
                target="_blank" 
                rel="noopener noreferrer"
                className="inline-block mt-2 px-3 py-1.5 rounded-lg text-xs font-medium transition-all"
                style={{ background: "hsl(45 60% 50%)", color: "hsl(270 50% 5%)" }}
              >
                Связаться с поддержкой
              </a>
            </div>
          </div>
        )}
        
        <label className="text-sm mb-1 block" style={{ color: "hsl(260 15% 55%)" }}>Ссылка на Twitch канал</label>
        <input
          value={twitchLink}
          onChange={(e) => setTwitchLink(e.target.value)}
          placeholder="https://www.twitch.tv/yourname"
          className="w-full px-4 py-2.5 rounded-lg text-sm mb-4 outline-none transition-all focus:ring-1"
          style={{
            background: "hsl(270 35% 7%)",
            color: "hsl(260 20% 90%)",
            border: "1px solid hsl(270 25% 20%)",
          }}
        />
        <button 
          onClick={handleStartStream}
          disabled={starting}
          className="w-full py-3 rounded-lg font-semibold text-white glow-btn disabled:opacity-50"
        >
          {starting ? 'Запуск...' : 'Запустить Стрим'}
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
            <button 
              onClick={handleSaveSocial}
              disabled={loading}
              className="px-4 py-2 rounded-lg text-sm font-medium transition-all disabled:opacity-50" 
              style={{
                background: "hsl(270 25% 14%)",
                color: "hsl(260 15% 75%)",
                border: "1px solid hsl(270 25% 22%)"
              }}>
              {loading ? 'Сохранение...' : 'Добавить'}
            </button>
          </div>
        </div>

        <div className="glass-card glass-card-hover rounded-xl p-6 transition-all">
          <div className="flex items-center gap-3 mb-4">
            <Bell size={22} style={{ color: "hsl(90 85% 55%)" }} />
            <h2 className="text-lg font-bold" style={{ color: "hsl(260 20% 90%)" }}>Уведомления</h2>
          </div>
          {notifications.length === 0 ? (
            <p className="text-sm text-center py-4" style={{ color: "hsl(260 15% 50%)" }}>
              Нет уведомлений
            </p>
          ) : (
            <div className="flex flex-col gap-2">
              {notifications.map((n, i) => (
                <div key={n.id} className="px-4 py-2.5 rounded-lg text-sm transition-all" style={{
                  background: "hsl(270 35% 7%)",
                  color: "hsl(260 15% 55%)",
                  border: i === 0 ? "1px solid hsl(90 85% 45% / 0.3)" : "1px solid hsl(270 25% 18%)",
                  boxShadow: i === 0 ? "0 0 10px hsl(90 85% 45% / 0.05)" : "none",
                }}>
                  {n.message}
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default TrafficPage;
