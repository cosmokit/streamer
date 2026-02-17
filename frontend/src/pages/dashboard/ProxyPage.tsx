import { useState } from "react";
import { X, Shield, Upload } from "lucide-react";

const ProxyPage = () => {
  const [showModal, setShowModal] = useState(false);

  return (
    <div className="p-4 md:p-8 max-w-5xl">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div className="flex items-center gap-3">
          <Shield size={24} style={{ color: "hsl(90 85% 55%)" }} />
          <div>
            <h1 className="text-xl md:text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Мои Прокси</h1>
            <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Управление вашими прокси-подключениями</p>
          </div>
        </div>
        <div className="flex gap-2 sm:gap-3">
          <button onClick={() => setShowModal(true)} className="px-3 sm:px-5 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium text-white glow-btn flex items-center gap-2">
            <Upload size={15} /> <span className="hidden sm:inline">Загрузить Список</span><span className="sm:hidden">Загрузить</span>
          </button>
          <button className="px-3 sm:px-5 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all" style={{
            background: "hsl(270 25% 14%)",
            color: "hsl(260 15% 75%)",
            border: "1px solid hsl(270 25% 22%)"
          }}>Активировать</button>
        </div>
      </div>

      <div className="glass-card rounded-xl p-8 mt-4 min-h-[250px] flex flex-col items-center justify-center relative overflow-hidden" style={{
        borderStyle: "dashed",
        borderColor: "hsl(270 35% 23% / 0.5)",
      }}>
        <div className="absolute inset-0 pointer-events-none" style={{
          background: "radial-gradient(ellipse at center, hsl(90 85% 45% / 0.04), transparent 60%)"
        }} />
        <Shield size={40} className="mb-3" style={{ color: "hsl(270 20% 22%)" }} />
        <h2 className="text-lg font-semibold mb-1" style={{ color: "hsl(260 20% 70%)" }}>Список прокси</h2>
        <p className="text-base font-medium mb-1" style={{ color: "hsl(260 15% 50%)" }}>Прокси не добавлены</p>
        <p className="text-sm" style={{ color: "hsl(260 15% 35%)" }}>Загрузите список прокси для начала работы</p>
      </div>

      <div className="glass-card rounded-xl p-6 mt-8">
        <h3 className="font-semibold mb-1" style={{ color: "hsl(260 20% 90%)" }}>Нужна помощь с активацией прокси?</h3>
        <p className="text-sm mb-4" style={{ color: "hsl(260 15% 50%)" }}>Свяжитесь с технической поддержкой для активации прокси и получения помощи.</p>
        <a href="https://t.me/profitstream_support" target="_blank" rel="noopener noreferrer"
          className="inline-block px-5 py-2.5 rounded-lg text-sm font-medium transition-all"
          style={{ background: "hsl(270 25% 14%)", color: "hsl(260 15% 75%)", border: "1px solid hsl(270 25% 22%)" }}>
          Связаться с поддержкой
        </a>
      </div>

      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center" style={{ background: "hsl(270 50% 3% / 0.85)", backdropFilter: "blur(8px)" }}>
          <div className="glass-card rounded-xl p-6 w-full max-w-md relative" style={{
            boxShadow: "0 0 40px hsl(270 75% 45% / 0.15), 0 25px 50px hsl(270 50% 3% / 0.5)"
          }}>
            <button onClick={() => setShowModal(false)} className="absolute top-4 right-4 transition-colors" style={{ color: "hsl(260 15% 50%)" }}>
              <X size={20} />
            </button>
            <h2 className="text-lg font-bold mb-1 glow-text" style={{ color: "hsl(260 20% 93%)" }}>Загрузить Список Прокси</h2>
            <p className="text-sm mb-4" style={{ color: "hsl(260 15% 50%)" }}>Выберите .txt файл</p>
            <input type="file" accept=".txt" className="w-full px-4 py-2.5 rounded-lg text-sm mb-4"
              style={{ background: "hsl(270 35% 7%)", color: "hsl(260 15% 55%)", border: "1px solid hsl(270 25% 20%)" }} />
            <button className="w-full py-3 rounded-lg font-semibold text-white glow-btn">Загрузить</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default ProxyPage;
