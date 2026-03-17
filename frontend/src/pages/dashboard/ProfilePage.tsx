import { useState, useEffect } from "react";
import { User, Send } from "lucide-react";

interface UserData {
  name: string;
  telegram: string | null;
  twitch: string | null;
}

const ProfilePage = () => {
  const [user, setUser] = useState<UserData | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/me', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(res => res.json())
      .then(response => {
        setUser(response.data);
        setLoading(false);
      })
      .catch(err => {
        console.error('Error loading profile:', err);
        setLoading(false);
      });
  }, []);

  if (loading) {
    return (
      <div className="p-6 md:p-8 max-w-2xl">
        <div className="text-center" style={{ color: "hsl(var(--muted-foreground))" }}>
          Загрузка...
        </div>
      </div>
    );
  }

  if (!user) {
    return (
      <div className="p-6 md:p-8 max-w-2xl">
        <div className="text-center" style={{ color: "hsl(var(--muted-foreground))" }}>
          Ошибка загрузки профиля
        </div>
      </div>
    );
  }

  const initials = (user?.name || 'U').split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);

  return (
    <div className="p-4 md:p-6 lg:p-8 w-full max-w-2xl mx-auto">
      <h1 className="text-xl font-bold mb-6" style={{ color: "hsl(var(--foreground))" }}>
        Мой профиль
      </h1>

      <div className="glass-card rounded-xl p-5 mb-5 flex items-center gap-4">
        <div className="relative">
          <div className="w-16 h-16 rounded-full flex items-center justify-center text-xl font-bold"
            style={{
              background: "linear-gradient(135deg, hsl(var(--primary)), hsl(var(--accent)))",
              color: "hsl(var(--primary-foreground))",
            }}>
            {initials}
          </div>
        </div>
        <div>
          <div className="font-semibold" style={{ color: "hsl(var(--foreground))" }}>{user.name}</div>
          <div className="text-xs" style={{ color: "hsl(var(--muted-foreground))" }}>Участник с Января 2025</div>
        </div>
      </div>

      <div className="glass-card rounded-xl p-5 mb-5">
        <h2 className="text-sm font-semibold mb-4" style={{ color: "hsl(var(--foreground))" }}>Личные данные</h2>
        <div className="flex flex-col gap-3">
          <div>
            <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>
              Username
            </label>
            <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={{
              background: "hsl(var(--input))",
              border: "1px solid hsl(var(--border))",
            }}>
              <User size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
              <div className="text-sm flex-1" style={{ color: "hsl(var(--foreground))" }}>
                {user.name}
              </div>
            </div>
          </div>

          <div>
            <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>
              Telegram
            </label>
            <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={{
              background: "hsl(var(--input))",
              border: "1px solid hsl(var(--border))",
            }}>
              <Send size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
              <div className="text-sm flex-1" style={{ color: user.telegram ? "hsl(var(--foreground))" : "hsl(var(--muted-foreground))" }}>
                {user.telegram || "Не указан"}
              </div>
            </div>
          </div>

          <div>
            <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>
              Twitch
            </label>
            <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={{
              background: "hsl(var(--input))",
              border: "1px solid hsl(var(--border))",
            }}>
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714Z" fill="currentColor"/>
              </svg>
              <div className="text-sm flex-1" style={{ color: user.twitch ? "hsl(var(--foreground))" : "hsl(var(--muted-foreground))" }}>
                {user.twitch || "Не указан"}
              </div>
            </div>
          </div>
        </div>

        <div className="mt-5 p-3 rounded-lg" style={{ background: "hsl(var(--accent) / 0.1)", border: "1px solid hsl(var(--accent) / 0.3)" }}>
          <p className="text-xs" style={{ color: "hsl(var(--muted-foreground))" }}>
            Для изменения Telegram или Twitch обратитесь к администратору
          </p>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div className="glass-card rounded-xl p-5">
          <h2 className="text-sm font-semibold mb-3" style={{ color: "hsl(var(--foreground))" }}>Подписка</h2>
          <div className="flex items-center gap-3 mb-1">
            <span className="px-2.5 py-1 rounded-md text-[11px] font-bold"
              style={{ background: "hsl(var(--accent) / 0.15)", color: "hsl(var(--accent))" }}>
              ЛАЙТ
            </span>
            <span className="text-sm" style={{ color: "hsl(var(--foreground))" }}>Активна до 15 марта 2026</span>
          </div>
          <p className="text-xs" style={{ color: "hsl(var(--muted-foreground))" }}>
            Базовый доступ к инструментам
          </p>
        </div>

        <div className="glass-card rounded-xl p-5 flex flex-col justify-between relative overflow-hidden" style={{ border: "1px solid hsl(var(--primary) / 0.3)" }}>
          <div className="absolute inset-0 opacity-10" style={{ background: "radial-gradient(circle at top right, hsl(270 75% 55%), transparent 70%)" }} />
          <div className="relative">
            <div className="flex items-center gap-2 mb-2">
              <span className="px-2.5 py-1 rounded-md text-[11px] font-bold"
                style={{ background: "linear-gradient(135deg, hsl(270 75% 55% / 0.2), hsl(270 75% 45% / 0.3))", color: "hsl(270 75% 75%)", border: "1px solid hsl(270 75% 55% / 0.3)" }}>
                ✦ PRO
              </span>
            </div>
            <p className="text-xs mb-4" style={{ color: "hsl(var(--muted-foreground))" }}>
              Полный доступ ко всем инструментам и приоритетная поддержка
            </p>
          </div>
          <button 
            className="glow-btn w-full py-2.5 rounded-lg text-sm font-medium relative"
            style={{ color: "hsl(var(--primary-foreground))" }}
          >
            Перейти на PRO
          </button>
        </div>
      </div>
    </div>
  );
};

export default ProfilePage;
