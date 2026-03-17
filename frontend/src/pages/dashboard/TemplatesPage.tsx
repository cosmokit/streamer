import { useState, useEffect } from "react";
import { Layout, Download } from "lucide-react";
import { toast } from "@/hooks/use-toast";

interface Template {
  id: number;
  category: string;
  name: string;
  description: string;
  download_url: string;
}

const TemplatesPage = () => {
  const [templates, setTemplates] = useState<Template[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/templates', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(res => res.json())
      .then(data => {
        setTemplates(data.data || []);
        setLoading(false);
      })
      .catch(err => {
        console.error('Error loading templates:', err);
        toast({ title: "Ошибка", description: "Не удалось загрузить шаблоны", variant: "destructive" });
        setLoading(false);
      });
  }, []);

  const handleDownload = (template: Template) => {
    if (template.download_url === '#' || !template.download_url) {
      toast({ 
        title: "Недоступно", 
        description: "Шаблон ещё не загружен. Обратитесь к администратору.", 
        variant: "destructive" 
      });
      return;
    }

    window.open(template.download_url, '_blank');
  };

  if (loading) {
    return (
      <div className="p-4 md:p-6 lg:p-8 w-full flex items-center justify-center min-h-[400px]">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 mx-auto mb-4" style={{ borderColor: "hsl(270 75% 50%)" }}></div>
          <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Загрузка шаблонов...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="p-4 md:p-6 lg:p-8 w-full">
      <div className="flex items-center gap-3 mb-6">
        <Layout size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <div>
          <h1 className="text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Шаблоны</h1>
          <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Готовые шаблоны для ваших стримов</p>
        </div>
      </div>

      {templates.length === 0 ? (
        <div className="glass-card rounded-xl p-8 text-center">
          <Layout size={48} className="mx-auto mb-4 opacity-20" />
          <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Шаблоны пока не добавлены</p>
        </div>
      ) : (
        <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
          {templates.map((template, i) => (
            <div key={template.id} className="glass-card glass-card-hover rounded-xl overflow-hidden transition-all duration-300 group">
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
                }}>{template.category}</span>
                <h3 className="text-sm font-semibold mt-2" style={{ color: "hsl(260 20% 90%)" }}>{template.name}</h3>
                <p className="text-[11px] mt-1" style={{ color: "hsl(260 15% 45%)" }}>{template.description}</p>
              </div>
              <div className="px-4 pb-4">
                <button 
                  onClick={() => handleDownload(template)}
                  disabled={template.download_url === '#' || !template.download_url}
                  className="w-full py-2 rounded-lg text-xs font-semibold text-white glow-btn flex items-center justify-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <Download size={13} /> Скачать
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      <div className="glass-card rounded-xl p-6 mt-8">
        <h3 className="font-semibold mb-1" style={{ color: "hsl(260 20% 90%)" }}>Нужен индивидуальный шаблон?</h3>
        <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Свяжитесь с нами для создания уникального дизайна</p>
      </div>
    </div>
  );
};

export default TemplatesPage;
