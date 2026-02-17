const API_BASE = import.meta.env.PROD ? '/api' : 'http://localhost:8080/api';

interface ApiResponse<T> {
  data?: T;
  error?: string;
  message?: string;
}

class Api {
  private async request<T>(endpoint: string, options?: RequestInit): Promise<ApiResponse<T>> {
    try {
      const response = await fetch(`${API_BASE}${endpoint}`, {
        ...options,
        headers: {
          'Content-Type': 'application/json',
          ...options?.headers,
        },
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('API Error:', error);
      return { error: error instanceof Error ? error.message : 'Unknown error' };
    }
  }

  private async get<T>(endpoint: string): Promise<ApiResponse<T>> {
    return this.request<T>(endpoint);
  }

  private async post<T>(endpoint: string, data?: any): Promise<ApiResponse<T>> {
    return this.request<T>(endpoint, {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  // Auth
  async getMe() {
    return this.get<any>('/me');
  }

  // Proxies
  async getProxies() {
    return this.get<any[]>('/proxies');
  }

  async uploadProxies(file: File) {
    const formData = new FormData();
    formData.append('file', file);

    try {
      const response = await fetch(`${API_BASE}/proxies/upload`, {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('Upload Error:', error);
      return { error: error instanceof Error ? error.message : 'Upload failed' };
    }
  }

  async activateProxies() {
    return this.post('/proxies/activate');
  }

  // Stream Runs
  async startStream(twitchUrl: string) {
    return this.post('/stream-runs/start', { twitch_url: twitchUrl });
  }

  // Notifications
  async getNotifications() {
    return this.get<any[]>('/notifications');
  }

  // Social Links
  async getSocialLinks() {
    return this.get<any>('/social-links');
  }

  async updateSocialLinks(xUrl: string) {
    return this.post('/social-links', { x_url: xUrl });
  }

  // Videos
  async getVideos() {
    return this.get<any[]>('/videos');
  }

  async getVideosSummary() {
    return this.get<any>('/videos/summary');
  }

  // Templates
  async getTemplates() {
    return this.get<any[]>('/templates');
  }

  // Help
  async getHelp() {
    return this.get<any[]>('/help');
  }

  // Progress
  async getProgress() {
    return this.get<any>('/progress');
  }

  async getProgressSteps() {
    return this.get<any>('/progress/steps');
  }

  async completeStep(stepKey: string) {
    return this.post(`/progress/steps/${stepKey}/complete`);
  }
}

export const api = new Api();
