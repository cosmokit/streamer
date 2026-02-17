import { Layout, Download } from "lucide-react";

const templates = [
  "Boy&Girls", "Wizard", "Knigth", "Girl",
  "Gollum", "Cowboy", "Panda", "Wizard 2",
  "Mustang", "Robot", "Mario", "Crow",
  "Cat", "Sword", "Knight with rose", "Harry P",
  "Son Jln", "Horror", "Gta",
];

const TemplatesPage = () => {
  return (
    <div className="p-4 md:p-8 max-w-5xl">
      <div className="flex items-center gap-3 mb-6">
        <Layout size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <div>
          <h1 className="text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Шаблоны</h1>
          <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Готовые шаблоны для ваших стримов</p>
        </div>
      </div>

      <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
        {templates.map((name, i) => (
          <div key={name} className="glass-card glass-card-hover rounded-xl overflow-hidden transition-all duration-300 group">
            <div className="h-24 relative" style={{
              background: `linear-gradient(135deg, hsl(${270 + i * 4} 45% 13%), hsl(${90 + i * 2} 40% 8%))`,
            }}>
              <div className="absolute inset-0 flex items-center justify-center opacity-20">
                <Layout size={32} />
              </div>
            </div>
            <div className="p-4">
              <span className="text-[10px] px-2 py-0.5 rounded font-bold" style={{
                background: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))",
                color: "#fff"
              }}>Gaming</span>
              <h3 className="text-sm font-semibold mt-2" style={{ color: "hsl(260 20% 90%)" }}>{name}</h3>
              <p className="text-[11px] mt-1" style={{ color: "hsl(260 15% 45%)" }}>Avatar, banner and offline banner</p>
            </div>
            <div className="px-4 pb-4">
              <button className="w-full py-2 rounded-lg text-xs font-semibold text-white glow-btn flex items-center justify-center gap-1.5">
                <Download size={13} /> Скачать
              </button>
            </div>
          </div>
        ))}
      </div>

      <div className="glass-card rounded-xl p-6 mt-8">
        <h3 className="font-semibold mb-1" style={{ color: "hsl(260 20% 90%)" }}>Нужен индивидуальный шаблон?</h3>
        <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Свяжитесь с нами для создания уникального дизайна</p>
      </div>
    </div>
  );
};

export default TemplatesPage;
