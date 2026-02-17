import { useState } from "react";
import { User, Mail, Lock, Camera, Save } from "lucide-react";
import { toast } from "@/hooks/use-toast";

const ProfilePage = () => {
  const [editing, setEditing] = useState(false);

  return (
    <div className="p-6 md:p-8 max-w-2xl">
      <h1 className="text-2xl font-bold mb-1 glow-text" style={{ color: "hsl(var(--foreground))" }}>
        Мой профиль
      </h1>
      <p className="text-sm mb-8" style={{ color: "hsl(var(--muted-foreground))" }}>
        Управление личными данными и настройками аккаунта
      </p>

      {/* Avatar section */}
      <div className="glass-card rounded-xl p-6 mb-6">
        <div className="flex items-center gap-5">
          <div className="relative">
            <div className="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold"
              style={{
                background: "linear-gradient(135deg, hsl(var(--primary)), hsl(var(--accent)))",
                color: "hsl(var(--primary-foreground))",
              }}>
              SP
            </div>
            <button className="absolute -bottom-1 -right-1 w-7 h-7 rounded-full flex items-center justify-center"
              style={{
                background: "hsl(var(--secondary))",
                border: "2px solid hsl(var(--border))",
                color: "hsl(var(--muted-foreground))",
              }}>
              <Camera size={13} />
            </button>
          </div>
          <div>
            <div className="text-lg font-semibold" style={{ color: "hsl(var(--foreground))" }}>StreamerPro</div>
            <div className="text-xs" style={{ color: "hsl(var(--muted-foreground))" }}>Участник с Января 2025</div>
          </div>
        </div>
      </div>

      {/* Info fields */}
      <div className="glass-card rounded-xl p-6 mb-6">
        <div className="flex items-center justify-between mb-5">
          <h2 className="text-sm font-semibold" style={{ color: "hsl(var(--foreground))" }}>Личные данные</h2>
          <button
            onClick={() => setEditing(!editing)}
            className="text-xs px-3 py-1.5 rounded-lg font-medium transition-all"
            style={{
              background: editing ? "hsl(var(--accent) / 0.15)" : "hsl(var(--secondary))",
              color: editing ? "hsl(var(--accent))" : "hsl(var(--muted-foreground))",
              border: `1px solid ${editing ? "hsl(var(--accent) / 0.3)" : "hsl(var(--border))"}`,
            }}
          >
            {editing ? "Отмена" : "Редактировать"}
          </button>
        </div>
        <div className="flex flex-col gap-4">
          {[
            { icon: User, label: "Имя пользователя", value: "StreamerPro" },
            { icon: Mail, label: "Email", value: "streamer@example.com" },
            { icon: Lock, label: "Пароль", value: "••••••••" },
          ].map((field, i) => (
            <div key={i}>
              <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>
                {field.label}
              </label>
              <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={{
                background: "hsl(var(--input))",
                border: `1px solid ${editing ? "hsl(var(--primary) / 0.4)" : "hsl(var(--border))"}`,
              }}>
                <field.icon size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
                <input
                  className="bg-transparent text-sm flex-1 outline-none"
                  style={{ color: "hsl(var(--foreground))" }}
                  defaultValue={field.value}
                  readOnly={!editing}
                  type={i === 2 ? "password" : "text"}
                />
              </div>
              {i === 2 && (
                <a href="/dashboard/reset-password" className="text-[11px] mt-1.5 inline-block transition-colors hover:underline"
                  style={{ color: "hsl(var(--primary))" }}>
                  Сменить пароль →
                </a>
              )}
            </div>
          ))}
        </div>
        {editing && (
          <button className="glow-btn mt-5 px-5 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2"
            style={{ color: "hsl(var(--primary-foreground))" }}
            onClick={() => {
              setEditing(false);
              toast({ title: "Сохранено", description: "Данные профиля успешно обновлены" });
            }}>
            <Save size={15} />
            Сохранить изменения
          </button>
        )}
      </div>

      {/* Subscription */}
      <div className="glass-card rounded-xl p-6">
        <h2 className="text-sm font-semibold mb-3" style={{ color: "hsl(var(--foreground))" }}>Подписка</h2>
        <div className="flex items-center gap-3 mb-2">
          <span className="px-2.5 py-1 rounded-md text-[11px] font-bold"
            style={{ background: "hsl(var(--accent) / 0.15)", color: "hsl(var(--accent))" }}>
            PRO
          </span>
          <span className="text-sm" style={{ color: "hsl(var(--foreground))" }}>Активна до 15 марта 2026</span>
        </div>
        <p className="text-xs" style={{ color: "hsl(var(--muted-foreground))" }}>
          Полный доступ ко всем инструментам платформы
        </p>
      </div>
    </div>
  );
};

export default ProfilePage;
